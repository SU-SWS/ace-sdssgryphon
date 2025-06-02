<?php

namespace Drupal\sdss_workgroup_tagging;

use GuzzleHttp\Client;

/**
 * Tag imported person content using workgroup membership.
 */
class SdssWgTaggingUtil {

  /**
   * Updates tag field in Stanford Person for nodes containing a sunetid.
   */
  public function tagPersons() {
    /** @var \Drupal\Core\Entity\EntityTypeManager $entity_manager */
    $entity_manager = \Drupal::service('entity_type.manager');

    // Match the config field names to their Person content type field names.
    $field_map = [
      'org-tag-term' => 'su_sdss_person_organization',
      'person-tag-term' => 'su_person_type_group',
    ];

    // Make sure we have the fields we need in the Stanford Person type.
    $all_bundle_fields = \Drupal::service('entity_field.manager')
      ->getFieldDefinitions('node', 'stanford_person');
    if (empty($all_bundle_fields['su_person_sunetid'])) {
      \Drupal::logger('sdss_workgroup_tagging')
        ->info('Person content type missing su_person_sunetid field.');
      return FALSE;
    }

    // Build an array of workgroups => taxonomy terms from config.
    $initial_tag_list = \Drupal::configFactory()
      ->getEditable('sdss_workgroup_tagging.settings')->get('tags');
    if (empty($initial_tag_list)) {
      \Drupal::logger('sdss_workgroup_tagging')
        ->info('No tags configured for workgroup tagging.');
      return FALSE;
    }

    // Initialize arrays of tags to remove and add.
    $remove = [
      'org-tag-term' => [],
      'person-tag-term' => [],
    ];
    $add = [];

    // Build the remove and add arrays from the configuration.
    foreach ($initial_tag_list as $tag) {
      $workgroup = $tag['workgroup'];
      $auto_remove = $tag['auto-removal'];
      // Do the next step separately for each of the taxonomy terms involved.
      foreach (['org-tag-term', 'person-tag-term'] as $field_name) {
        if (!empty($tag[$field_name])) {
          // Add terms to remove array if auto-removal is set for workgroup.
          if (!empty($auto_remove)) {
            $remove[$field_name] = $remove[$field_name] + $tag[$field_name];
          }
          // Add terms to the add array for this workgroup.
          $add[$workgroup][$field_name] = $tag[$field_name];
        }
      }
    }

    // Build an array of person nids to process to avoid multiple db writes.
    $process = [];
    // Add 'remove' terms for each person node that has it.
    foreach (['org-tag-term', 'person-tag-term'] as $field_name) {
      if (!empty($remove[$field_name])) {
        $persons = $entity_manager->getStorage('node')
          ->getQuery('AND')
          ->accessCheck(FALSE)
          ->condition('type', 'stanford_person', '=')
          ->condition($field_map[$field_name], $remove[$field_name], 'IN')
          ->execute();
        foreach ($persons as $person) {
          $process[$person]['remove'][$field_name] = $remove[$field_name];
        }
      }
    }

    // For each person in a workgroup, add terms to include to process array.
    foreach ($add as $workgroup => $terms) {
      // Get the sunetids for people in a workgroup.
      $members = self::getWgMembers($workgroup);
      if (!empty($members['members'])) {
        // Flip the sunetid keys to values to use in query.
        $sunets = array_flip($members['members']);
        // Get the person node ids that match the sunet ids from the workgroup.
        $persons = $entity_manager->getStorage('node')
          ->getQuery('AND')
          ->accessCheck(FALSE)
          ->condition('type', 'stanford_person', '=')
          ->condition('su_person_sunetid', $sunets, 'IN')
          ->execute();
        // Add instruction to process to add terms for each person found.
        foreach ($persons as $person) {
          $process[$person]['add'] = $terms;
        }
      }
    }

    // Load the node objects for all the persons we are processing.
    $node_ids = [];
    foreach ($process as $entity_id => $items) {
      $node_ids[] = $entity_id;
    }
    $nodes = $entity_manager->getStorage('node')->loadMultiple($node_ids);

    // Add/Remove terms for each person.
    foreach ($nodes as $node) {
      $nid = $node->id();
      $updated = FALSE;
      // For each taxonomy reference field, get the currently set terms values.
      foreach (['org-tag-term', 'person-tag-term'] as $field_name) {
        $field = $field_map[$field_name];
        $terms = $node->get($field)->getValue();
        // The updated value of the reference field will come from new_terms.
        $new_terms = [];
        // For removal, keep terms by copying them to the new_terms array.
        // Do not keep if term is in remove array and *not* in add array.
        foreach ($terms as $term) {
          if (!empty($process[$nid]['remove'][$field_name][$term['target_id']])
            && empty($process[$nid]['add'][$field_name][$term['target_id']])) {
            // If we are removing a term, set updated to true for this node.
            $updated = TRUE;
          }
          else {
            $new_terms[] = ['target_id' => $term['target_id']];
          }
        }
        // For each term to add, do so if not already in new_terms.
        foreach ($process[$nid]['add'][$field_name] as $tid) {
          $found = FALSE;
          foreach ($new_terms as $new_term) {
            if ($new_term['target_id'] == $tid) {
              $found = TRUE;
              break;
            }
          }
          if (!$found) {
            $new_terms[] = ['target_id' => $tid];
            // Set updated to true for the node if a term was really added.
            $updated = TRUE;
          }
        }
        // Update the field value if warranted.
        if ($updated) {
          $node->set($field, $new_terms);
        }
      }
      // Only save the node back to the database if it was changed.
      if ($updated) {
        $node->save();
      }
    }
    // All done.
    return TRUE;
  }

  /**
   * Queries the Workgroup API and returns workgroup members in an array.
   *
   * @param string $workgroup
   *   The name of the workgroup.
   *
   * @return array
   *   An array of workgroup members.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public static function getWgMembers(string $workgroup) {
    // Initialize the return array.
    $status = ['workgroup' => $workgroup, 'member_count' => 0];

    // Ensure the logger is initialized properly.
    $logger = \Drupal::logger('sdss_workgroup_tagging');

    try {
      // Get the MAIS Workgroup API cert from the stanford_samlauth config.
      $key = '';
      $cert = '';
      $config = \Drupal::configFactory()->get('stanford_samlauth.settings');
      $cert_path = $config->get('role_mapping.workgroup_api.cert');
      $key_path = $config->get('role_mapping.workgroup_api.key');
      // Make sure we can access the cert files.
      if ($cert_path && is_file($cert_path) && $key_path && is_file($key_path)) {
        $key = $key_path;
        $cert = $cert_path;
      }
      if (empty($key) || empty($cert)) {
        $error_message = 'Error getting workgroup ' . $workgroup .
          '. Workgroup API credentials have not been set.';
        $logger->notice($error_message);
        $status['message'] = $error_message;
        return ['members' => [], 'status' => $status];
      }

      // Call the API.
      $http_client = new Client();
      $result = $http_client->request('GET',
        'https://workgroupsvc.stanford.edu/v1/workgroups/' . $workgroup,
        ['cert' => $cert, 'ssl_key' => $key]);
      if ($result->getStatusCode() != 200) {
        $error_message = 'Error getting workgroup ' . $workgroup . '. ' . $result->getReasonPhrase();
        $logger->error($error_message);
        $status['message'] = $error_message;
        return ['members' => [], 'status' => $status];
      }
      // Get the workgroup results XML.
      $xml = simplexml_load_string($result->getBody());
      $xpath = $xml->xpath('members');
      $id_attribute = 'id';
      $name_attribute = 'name';
      $sunets = [];
      if (is_array($xpath)) {
        $xpath0 = reset($xpath);
        if ($xpath0 !== FALSE) {
          // Get members and store them in array keyed by sunetid.
          $members = $xpath0->xpath('member');
          if (is_array($members)) {
            foreach ($members as $member) {
              $sunetid = (string) $member->attributes()->$id_attribute;
              $name = (string) $member->attributes()->$name_attribute;
              $sunets[$sunetid] = $name;
            }
          }
          // Get nested workgroups and recursively call this function.
          $workgroups = $xpath0->xpath('workgroup');
          if (is_array($workgroups)) {
            foreach ($workgroups as $next_wg) {
              $nested = (string) $next_wg->attributes()->$id_attribute;
              $subsunets = self::getWgMembers($nested);
              if ($subsunets['status']['member_count'] > 0) {
                $sunets = array_merge($sunets, $subsunets['members']);
              }
            }
          }
        }
      }
      if (!empty($sunets)) {
        $status['member_count'] = count($sunets);
        $status['message'] = 'okay';
      }
      else {
        $status['message'] = 'Empty workgroups may not be used.';
        $logger->error($status['message']);
      }
      // Return the results.
      return ['members' => $sunets, 'status' => $status];
    }
    catch (\Exception $e) {
      $error_message = 'Error getting workgroup ' . $workgroup . '. ' . $e->getMessage();
      $logger->error($error_message);
      $status['message'] = $error_message;
      return ['members' => [], 'status' => $status];
    }
  }

}
