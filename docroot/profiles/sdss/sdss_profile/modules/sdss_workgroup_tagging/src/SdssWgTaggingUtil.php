<?php

namespace Drupal\sdss_workgroup_tagging;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Batch\BatchBuilder;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

class SdssWgTaggingUtil {

  /**
   * Tag imported person content using workgroup membership.
   */

  /**
   *
   * Updates su_person_wg_tags field in Stanford Person content type
   * for nodes containing a sunetid.
   *
   */
  public function tagPersons() {
    /** @var \Drupal\Core\Entity\EntityTypeManager $em */
    $em = \Drupal::service('entity_type.manager');
    /** @var \Drupal\Core\Entity\EntityFieldManager $fm */
    $fm = \Drupal::service('entity_field.manager');

    // Make sure we have the fields we need in the Stanford Person type.
    $all_bundle_fields = $fm
      ->getFieldDefinitions('node',
        'stanford_person');
    if (empty($all_bundle_fields['su_sdss_person_wg_tags']) ||
      empty($all_bundle_fields['su_person_sunetid'])) {
      \Drupal::logger('sdss_workgroup_tagging')
        ->info('Person content type missing su_person_sunetid and/or su_sdss_workgroups fields.');
      return false;
    }

    // Build an array of workgroups => taxonomy terms from config.
    $config = \Drupal::configFactory()
      ->getEditable('sdss_workgroup_tagging.settings');
    $initial_tagList = $config->get('tags');
    // Merge tag arrays for the same workgroups
    $tags = [];
    foreach ($initial_tagList as $tag) {
      $wg = $tag['workgroup'];
      $terms = $tag['tag-term'];
      if (empty($tags[$wg])) {
        $tags[$wg] = $terms;
      }
      else {
        $tags[$wg] = array_merge($tags[$wg], $terms);
      }
    }

    if (empty($tags)) {
        \Drupal::logger('sdss_workgroup_tagging')
          ->info('No workgroup tags available.');
      return false;
    }

    // Get all the stanford_person nodes that have a SUNet ID
    $storage_handler = $em->getStorage('node');
    $persons = $storage_handler->getQuery('AND')
      ->accessCheck(FALSE)
      ->condition('type', 'stanford_person', '=')
      ->condition('su_person_sunetid', NULL, 'IS NOT NULL')
      ->execute();

    // loop for each workgroup
    foreach ($tags as $wg => $terms) {
      $members = self::getWgMembers($wg);

      // For each person, get the workgroups that person belongs to.
      foreach ($persons as $entity_id) {
        $personNode = $em->getStorage('node')->load((int) $entity_id);
        $sunetid = $personNode->get('su_person_sunetid')->value;
        if (!empty($sunetid)) {
          if (array_key_exists($sunetid, $members['members'])) {
            $wg_terms = $personNode->get('su_sdss_person_wg_tags')->getValue();
            $update = false;
            foreach ($terms as $term) {
              $found = false;
              foreach ($wg_terms as $wg_term) {
                if ($wg_term['target_id'] == $term) {
                  $found = true;
                  continue;
                }
              }
              if (!$found) {
                $wg_terms[] = ['target_id' => $term];
                $update = true;
              }
            }
            if ($update) {
              $personNode->su_sdss_person_wg_tags = $wg_terms;
              $personNode->save();
            }
          }
        }
      }
    }
    return true;
  }

  public static function getTerms() {
    EntityTypeManagerInterface:
    $query = \Drupal::entityQuery('taxonomy_term');
    $query->condition('vid', "tags");
    $tids = $query->execute();
    $terms = \Drupal\taxonomy\Entity\Term::loadMultiple($tids);
    foreach ($terms as $term) {
      var_dump($term->name->value);
    }
  }

  /**
   * Queries the Workgroup API and returns workgroup members in an array.
   *
   * @param string $wg
   *   The name of the workgroup.
   * @param string $certin
   *   The SSL certificate allowing access to the Workgroup API.
   * @param string $keyin
   *   The SSL key allowing access to the Workgroup API.
   *
   * @return array
   *   An array of workgroup members.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public static function getWgMembers(string $wg) {
    $status = ['workgroup' => $wg, 'member_count' => 0];
    try {
      $logger = \Drupal::logger('sdss_workgroup_tagging');
      $key = '';
      $cert = '';
      $config = \Drupal::configFactory()->get('stanford_samlauth.settings');
      $cert_path = $config->get('role_mapping.workgroup_api.cert');
      $key_path = $config->get('role_mapping.workgroup_api.key');
      if ($cert_path && is_file($cert_path) && $key_path && is_file($key_path)) {
        $key = $key_path;
        $cert = $cert_path;
      }
      if (empty($key) || empty($cert)) {
        $errmsg = 'Error getting workgroup ' . $wg .
          '. Workgroup API credentials have not been set.';
        $logger->notice($errmsg);
        $status['message'] = $errmsg;
        return ['members' => [], 'status' => $status];
      }

      $httpClient = new \GuzzleHttp\Client();
      $result = $httpClient->request('GET',
        'https://workgroupsvc.stanford.edu/v1/workgroups/' . $wg,
        ['cert' => $cert, 'ssl_key' => $key]);
      if ($result->getStatusCode() != 200) {
        $errmsg = 'Error getting workgroup ' . $wg . '. ' . $result->getReasonPhrase();
        $logger->error($errmsg);
        $status['message'] = $errmsg;
        return ['members' => [], 'status' => $status];
      }
      $xml = simplexml_load_string($result->getBody());
      $xpath = $xml->xpath('members');
      $id_attribute = 'id';
      $name_attribute = 'name';
      $sunets = [];
      if (is_array($xpath)) {
        $xpath0 = reset($xpath);
        if ($xpath0 !== FALSE) {
          $members = $xpath0->xpath('member');
          if (is_array($members)) {
            foreach ($members as $member) {
              $sunetid = (string) $member->attributes()->$id_attribute;
              $name = (string) $member->attributes()->$name_attribute;
              $sunets[$sunetid] = $name;
            }
          }
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
      return ['members' => $sunets, 'status' => $status];
    }
    catch (\Exception $e) {

      $errmsg = 'Error getting workgroup ' . $wg . '. ' . $e->getMessage();
      $logger->error($errmsg);
      $status['message'] = $errmsg;
      return ['members' => [], 'status' => $status];
    }
  }

}
