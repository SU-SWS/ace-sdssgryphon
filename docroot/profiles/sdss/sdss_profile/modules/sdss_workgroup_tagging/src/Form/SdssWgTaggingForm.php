<?php

namespace Drupal\sdss_workgroup_tagging\Form;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Configuration for SDSS Workgroup tagging.
 */
class SdssWgTaggingForm extends ConfigFormBase {

  /**
   * The configuration factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $emInterface;

  /**
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The ConfigFactory interface.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $emInterface
   *   The EntityTypeManager interface.
   */
  public function __construct(
    ConfigFactoryInterface $configFactory,
    EntityTypeManagerInterface $emInterface,
  ) {
    $this->configFactory = $configFactory;
    $this->emInterface = $emInterface;
    parent::__construct($configFactory);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'sdss_workgroup_tagging.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'sdss_workgroup_tagging_config';
  }

  /**
   * Populates term arrays for Organization and Person Types for select fields.
   *
   * @return array
   */
  private function getTaxonomyTerms() {
    // Populate the term arrays for the select fields and validation.
    $terms = [];
    foreach (['sdss_organization', 'stanford_person_types'] as $vid) {
      $levels = [];
      $termObjs = $this->emInterface->getStorage('taxonomy_term')
        ->loadTree($vid);
      foreach ($termObjs as $term) {
        $terms[$vid][$term->tid] = $term->name;
      }
    }
    return $terms;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    // Populate the term arrays for the select fields.
    $terms = $this->getTaxonomyTerms();

    // Get the existing configuration.
    $config = $this->config('sdss_workgroup_tagging.settings');
    $tag_defaults = $config->get('tags');
    if (empty($tag_defaults) || !is_array($tag_defaults)) {
      // We need at least one fieldset to start.
      $tag_defaults = [
        [
          'workgroup' => NULL,
          'org-tag-term' => NULL,
          'person-tag-term' => NULL,
          'auto-removal' => 1,
        ],
      ];
    }

    // Get # of fieldsets based on a count in storage or size of defaults array.
    $fieldset_count = $form_state->get('fieldset_count');
    if (empty($fieldset_count) || intval($fieldset_count) < 1) {
      $fieldset_count = count($tag_defaults);
      $form_state->set('fieldset_count', $fieldset_count);
    }

    // Get a list of fields that were removed.
    $removed_fields = $form_state->get('removed_fields');
    if (empty($removed_fields) || !is_array($removed_fields)) {
      $removed_fields = [];
      $form_state->set('removed_fields', $removed_fields);
    }

    $form['tags_fieldsets'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Workgroup Tags'),
      '#prefix' => '<div id="sdss-wg-tag-fieldset-wrapper">',
      '#suffix' => '</div>',
    ];

    for ($i = 0; $i < $fieldset_count; $i++) {
      // Don't include a fieldset in the form if we know it was removed.
      if (in_array($i, $removed_fields)) {
        continue;
      }

      // Set the fieldnames for this fieldset and create it.
      $fieldset_name = 'tag-' . $i;
      $wg_fieldname = 'workgroup-' . $i;
      $org_tag_fieldname = 'org-tag-term-' . $i;
      $person_tag_fieldname = 'person-tag-term-' . $i;
      $auto_removal_fieldname = 'auto-removal-' . $i;
      $form['tags_fieldsets'][$fieldset_name] = [
        '#type' => 'fieldset',
        '#title' => '',
      ];

      // Build the fields for the fieldset including a default value if set.
      $wg_default = NULL;
      if (isset($tag_defaults[$i]['workgroup'])) {
        $wg_default = $tag_defaults[$i]['workgroup'];
      }
      $form['tags_fieldsets'][$fieldset_name][$wg_fieldname] = [
        '#type' => 'textfield',
        '#title' => $this->t('Workgroup'),
        '#default_value' => $wg_default,
      ];

      // Get the default organization terms and create the reference field.
      $org_tag_default = NULL;
      if (!empty($tag_defaults[$i]['org-tag-term'])) {
        $org_tag_default = $tag_defaults[$i]['org-tag-term'];
      }
      $form['tags_fieldsets'][$fieldset_name][$org_tag_fieldname] = [
        '#type' => 'select',
        '#title' => $this->t('Organization'),
        '#options' => $terms['sdss_organization'],
        '#multiple' => TRUE,
        '#default_value' => $org_tag_default,
        '#description' => $this->t('Select any Organizations with which to tag imported Persons in this workgroup.'),
      ];

      // Get the default person-type terms and create the reference field.
      $person_tag_default = NULL;
      if (!empty($tag_defaults[$i]['person-tag-term'])) {
        $person_tag_default = $tag_defaults[$i]['person-tag-term'];
      }
      $form['tags_fieldsets'][$fieldset_name][$person_tag_fieldname] = [
        '#type' => 'select',
        '#title' => $this->t('Person Type'),
        '#options' => $terms['stanford_person_types'],
        '#multiple' => TRUE,
        '#chosen' => TRUE,
        '#default_value' => $person_tag_default,
        '#description' => $this->t('Select any Person Types with which to tag imported Persons in this workgroup.'),
      ];

      // Get the default value for auto-removal of tags and create checkbox.
      $auto_removal_default = 1;
      if (isset($tag_defaults[$i]['auto-removal'])) {
        $auto_removal_default = $tag_defaults[$i]['auto-removal'];
      }
      $form['tags_fieldsets'][$fieldset_name][$auto_removal_fieldname] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Auto Remove Terms'),
        '#default_value' => $auto_removal_default,
        '#description' => $this->t('Automatically remove these tags from imported persons not in this workgroup.'),
        '#return_value' => 1,
      ];

      // Add a 'Remove' fieldset Ajax button.
      $form['tags_fieldsets'][$fieldset_name]['actions'] = [
        '#type' => 'submit',
        '#value' => $this->t('Remove'),
        '#name' => 'remove-' . $i,
        '#submit' => ['::removeOne'],
        '#ajax' => [
          'callback' => '::addremoveCallback',
          'wrapper' => 'sdss-wg-tag-fieldset-wrapper',
        ],
      ];

    }

    // Add 'Add' fieldset Ajax button and form submit button.
    $form['tags_fieldsets']['actions'] = [
      '#type' => 'actions',
    ];
    $form['tags_fieldsets']['actions']['add_tag'] = [
      '#name' => 'addone',
      '#type' => 'submit',
      '#value' => $this->t('Add one more'),
      '#submit' => ['::addOne'],
      '#ajax' => [
        'callback' => '::addremoveCallback',
        'wrapper' => 'sdss-wg-tag-fieldset-wrapper',
      ],
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * Callback for both ajax-enabled buttons.
   *
   * Selects and returns the fieldset.
   */
  public function addremoveCallback(array &$form, FormStateInterface $form_state) {
    return $form['tags_fieldsets'];
  }

  /**
   * Submit handler for the add  and remove Ajax buttons.
   *
   * Increments the field counter and causes a rebuild.
   */
  public function addOne(array &$form, FormStateInterface $form_state) {
    $fieldset_count = intval($form_state->get('fieldset_count'));
    $form_state->set('fieldset_count', $fieldset_count + 1);
    $form_state->setRebuild();
  }

  /**
   * Remove a Workgroup/Tag pair from the configuration.
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return void
   */
  public function removeOne(array &$form, FormStateInterface $form_state) {
    // See which remove button was triggered.
    $trigger = $form_state->getTriggeringElement();
    $indexToRemove = substr($trigger['#name'], 7);
    // If we don't find a button number, just return.
    if (!is_numeric($indexToRemove)) {
      return;
    }
    // Remove form_state values for the removed field.
    foreach (['workgroup-', 'org-tag-term-',
      'person-tag-term-', 'auto-removal-',
    ] as $field) {
      $fieldname = $field . $indexToRemove;
      $form_state->unsetValue($fieldname);
    }
    // Add the index of the removed fieldset to a storage array.
    $removed_fields = $form_state->get('removed_fields');
    $removed_fields[] = $indexToRemove;
    $form_state->set('removed_fields', $removed_fields);
    // Rebuild the form.
    $form_state->setRebuild();
  }

  /**
   * Validate the workgroup tags for inconsistencies in 'auto remove'.
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return void
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Check if this is being called from an Add or Remove button click.
    $trigger = $form_state->getTriggeringElement();
    $indexToRemove = $trigger['#name'];
    // If so, just return.
    if ($indexToRemove === 'addone' ||
      substr($trigger['#name'], 0, 7) === 'remove-') {
      return;
    }
    // Do parent validation first.
    parent::validateForm($form, $form_state);

    // Get term labels for error messages.
    $labels = [];
    $vocabs = $this->getTaxonomyTerms();
    foreach ($vocabs as $vocab) {
      $labels += $vocab;
    }

    // We will want to ensure that a workgroup is only listed once.
    $wg_list = [];
    // Loop through the fieldsets, excluding any that have been removed.
    $fieldset_count = $form_state->get('fieldset_count');
    $removed_fields = $form_state->get('removed_fields');
    $terms = [];
    for ($i = 0; $i < $fieldset_count; $i++) {
      if (in_array($i, $removed_fields)) {
        continue;
      }
      $workgroup = $form_state->getValue('workgroup-' . $i);
      if (array_search($workgroup, $wg_list) !== FALSE) {
        $form_state->setError($form['tags_fieldsets']['tag-' . $i]['workgroup-' . $i],
          $this->t('Workgroup "@wg" is listed more than once. Please add all terms for a workgroup into a single entry.',
            ['@wg' => $workgroup]));
        return;
      }

      // Check for inconsistencies in the auto-removal selections.
      // Build an array keyed by term with the wgs and auto-remove settings.
      $wg_list[] = $workgroup;
      $org_terms = $form_state->getValue('org-tag-term-' . $i);
      $person_terms = $form_state->getValue('person-tag-term-' . $i);
      $auto_removal = $form_state->getValue('auto-removal-' . $i);
      foreach ([$org_terms, $person_terms] as $vocab) {
        foreach ($vocab as $tid) {
          if (empty($terms[$tid])) {
            $terms[$tid] = [
              'label' => $labels[$tid],
              'wgs' => [],
            ];
          }
          $terms[$tid]['wgs'][$workgroup] = [
            'field_index' => $i,
            'auto_removal' => $auto_removal,
          ];
        }
      }
    }

    // Loop through each term and see if wgs have inconsistent auto-removal.
    foreach ($terms as $tid => $term) {
      $different = FALSE;
      $different_index = 0;
      $wg_string = '';
      $current_val = reset($term['wgs'])['auto_removal'];
      foreach ($term['wgs'] as $workgroup => $wg) {
        if (!empty($wg_string)) {
          $wg_string .= ', ';
        }
        $wg_string .= $workgroup;
        // If we see an inconsistency, set flag and note the field index.
        if ($current_val !== $wg['auto_removal']) {
          $different = TRUE;
          $different_index = $wg['field_index'];
        }
      }
      // If the flag is set, set the error message and return.
      if ($different) {
        $form_state->setError($form['tags_fieldsets']['tag-'
          . $different_index]['auto-removal-' . $different_index],
          $this->t('The term "@label" has inconsistent auto-remove settings for workgroups "@wgs".',
            ['@label' => $term['label'], '@wgs' => $wg_string]));
        return;
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    // Output the configuration for workgroup tagging.
    $fieldset_count = $form_state->get('fieldset_count');
    $removed_fields = $form_state->get('removed_fields');
    $tags = [];
    for ($i = 0; $i < $fieldset_count; $i++) {
      // Ignore fieldsets that have been removed.
      if (in_array($i, $removed_fields)) {
        continue;
      }
      $tags[] = [
        'workgroup' => $form_state->getValue('workgroup-' . $i),
        'org-tag-term' => $form_state->getValue('org-tag-term-' . $i),
        'person-tag-term' => $form_state->getValue('person-tag-term-' . $i),
        'auto-removal' => $form_state->getValue('auto-removal-' . $i),
      ];
    }
    $config = $this->configFactory->getEditable('sdss_workgroup_tagging.settings');
    $config->set('tags', $tags);
    $config->save();
  }

}
