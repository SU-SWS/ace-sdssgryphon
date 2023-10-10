<?php

namespace Drupal\sdss_layout_paragraphs\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\TypedConfigManagerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines a form for modifying SDSS Layout Paragraphs settings.
 */
class SdssLayoutParagraphsSettingsForm extends ConfigFormBase {

  /**
   * The typed config service.
   *
   * @var \Drupal\Core\Config\TypedConfigManagerInterface
   */
  protected $typedConfigManager;

  /**
   * SettingsForm constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\Core\Config\TypedConfigManagerInterface $typedConfigManager
   *   The typed config service.
   */
  public function __construct(
    ConfigFactoryInterface $config_factory,
    TypedConfigManagerInterface $typedConfigManager
  ) {
    parent::__construct($config_factory);
    $this->typedConfigManager = $typedConfigManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('config.typed')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'sdss_layout_paragraphs_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'sdss_layout_paragraphs.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->configFactory()->getEditable('sdss_layout_paragraphs.settings');

    $form['bg_color_options'] = [
      '#type' => 'textarea',
      '#title' => 'Background Color Options',
      '#description' => 'A list of background color options in key|pair format. Keys cannot have spaces',
      '#default_value' => $config->get('bg_color_options'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $lp_config = $this->configFactory()->getEditable('sdss_layout_paragraphs.settings');
    $lp_config->set('bg_color_options', $form_state->getValue('bg_color_options'));
    $lp_config->save();
    // Confirmation on form submission.
    $this->messenger()->addMessage($this->t('The SDSS Layout Paragraphs settings have been saved.'));
  }
}
