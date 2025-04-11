<?php

namespace Drupal\sdss_workgroup_tagging\Form;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\sdss_workgroup_tagging\SdssWgTaggingUtil;

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
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface;
   */
  protected $emInterface;

  /**
   * SdssWgTaggingForm constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The ConfigFactory interface.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $emInterface
   *    The EntityTypeManager interface.
   */
  public function __construct(ConfigFactoryInterface $configFactory,
    EntityTypeManagerInterface $emInterface) {
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
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $terms = [];
    foreach (['sdss_organization','stanford_person_types'] as $vid) {
      $termObjs = $this->emInterface->getStorage('taxonomy_term')
        ->loadByProperties(['vid' => $vid]);
      foreach ($termObjs as $tid => $term) {
        $terms[$tid] = $term->label();
      }
    }
    $tag_defaults = [];
    $user_input = $form_state->getUserInput();
    if (empty($user_input)) {
      $config = $this->config('sdss_workgroup_tagging.settings');
      if ($config->isNew()) {
        $config->initWithData(['tags'=>[]]);
      }
      $tag_defaults = $config->get('tags');
      if (!empty($tag_defaults)) {
        $lines = count($tag_defaults);
      } else {
        $lines = 1;
      }
      $form['lines'] = [
        '#type' => 'hidden',
        '#value' => $lines,
      ];
    } else {
      $lines = $form_state->get('lines');
      if (empty($lines) && !empty($user_input['lines'])) {
        $lines = $user_input['lines'];
      }
      if (empty($lines)) {
        $lines = 1;
      }
    }
    // Get a list of fields that were removed.
    $removed_fields = $form_state->get('removed_fields');
    if ($removed_fields === NULL) {
      $form_state->set('removed_fields', []);
      $removed_fields = [];
    }

    $form['tags_fieldsets'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Workgroup Tags'),
      '#prefix' => '<div id="sdss-wg-tag-fieldset-wrapper">',
      '#suffix' => '</div>',
    ];

    for ($i = 0; $i < $lines; $i++) {
      if (in_array($i, $removed_fields)) {
        continue;
      }
      $fieldset_name = 'tag-' . ($i + 1);
      $wg_fieldname = 'workgroup-'. ($i + 1);
      $tag_fieldname = 'tag-term-'. ($i + 1);
      $form['tags_fieldsets'][$fieldset_name] = [
        '#type' => 'fieldset',
        '#title' => '',
      ];
      $wg_default = null;
      if (isset($tag_defaults[$i])) {
        $wg_default = $tag_defaults[$i]['workgroup'];
      }
      $form['tags_fieldsets'][$fieldset_name][$wg_fieldname] = [
        '#type' => 'textfield',
        '#title' => $this->t('Workgroup'),
        '#default_value' => $wg_default,
      ];
      $tag_default = null;
      if (isset($tag_defaults[$i])) {
        $tag_default = $tag_defaults[$i]['tag-term'];
      }
      $form['tags_fieldsets'][$fieldset_name][$tag_fieldname] = [
        '#type' => 'select',
        '#title' => $this->t('Organization/Person Type'),
        '#options' => $terms,
        '#multiple' => true,
        '#default_value' => $tag_default,
      ];
      $form['tags_fieldsets'][$fieldset_name]['actions'] = [
        '#type' => 'submit',
        '#value' => $this->t('Remove'),
        '#name' => $i,
        '#submit' => ['::removeCallback'],
        '#ajax' => [
          'callback' => '::addmoreCallback',
          'wrapper' => 'sdss-wg-tag-fieldset-wrapper',
        ],
      ];
    }
    $form['tags_fieldsets']['actions'] = [
      '#type' => 'actions',
    ];
    $form['tags_fieldsets']['actions']['add_tag'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add one more'),
      '#submit' => ['::addOne'],
      '#ajax' => [
        'callback' => '::addmoreCallback',
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
  public function addmoreCallback(array &$form, FormStateInterface $form_state) {
    return $form['tags_fieldsets'];
  }

  /**
   * Submit handler for the "add-one-more" button.
   *
   * Increments the max counter and causes a rebuild.
   */
  public function addOne(array &$form, FormStateInterface $form_state) {
    $userInput = $form_state->getUserInput();
    $lines = $form_state->get('lines');
    if (empty($lines) && isset($userInput['lines'])) {
      $lines = $userInput['lines'];
    }
    $lines = strval(intval($lines)+1);
    $userInput['lines'] = $lines;
    $form_state->setUserInput($userInput);
    $form_state->set('lines', $lines);
    $form_state->setRebuild();
  }

  public function removeCallback(array &$form, FormStateInterface $form_state) {
    $trigger = $form_state->getTriggeringElement();
    $indexToRemove = $trigger['#name'];
    $wg_fieldname = 'workgroup-'. ($indexToRemove + 1);
    $tag_fieldname = 'tag-term-'. ($indexToRemove + 1);
    $form_state->unsetValue($wg_fieldname);
    $form_state->unsetValue($tag_fieldname);
    $removed_fields = $form_state->get('removed_fields');
    $removed_fields[] = $indexToRemove;
    $form_state->set('removed_fields', $removed_fields);
    $form_state->setRebuild();
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $config = $this->configFactory->getEditable('sdss_workgroup_tagging.settings');
    $lines = $form_state->get('lines');
    if (empty($lines)) {
      $userInput = $form_state->getUserInput();
      if (isset($userInput['lines'])) {
        $lines = $userInput['lines'];
      }
    }
    $tags = [];
    if (!empty($lines)) {
      $removed_fields = $form_state->get('removed_fields');
      if ($removed_fields === NULL) {
        $removed_fields = [];
      }
      for ($i = 0; $i < $lines; $i++) {
        if (in_array($i, $removed_fields)) {
          continue;
        }
        $tags[] = [
          'workgroup' => $form_state->getValue('workgroup-'. ($i + 1)),
          'tag-term' => $form_state->getValue('tag-term-'. ($i + 1)),
        ];
      }
    }
    $config->set('tags',$tags);
    $config->save();
  }

}
