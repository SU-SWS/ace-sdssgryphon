<?php

namespace Drupal\sdss_news_sharing\Form;

use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class NewsSharingSettingsForm.
 */
class NewsSharingSettingsForm extends ConfigFormBase {

  /**
   * Entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

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
  public function __construct(ConfigFactoryInterface $config_factory, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($config_factory);
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'news_sharing_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['sdss_news_sharing.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    $urls = $this->config('sdss_news_sharing.settings')->get('urls') ?: [];
    $status = $this->config('sdss_news_sharing.settings')->get('status') ?: 0;

    $form['status'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable News Importing'),
      '#description' => $this->t('Enable importing news from another SDSS site'),
      '#default_value' => $status,
    ];

    $user_guide_link = Link::fromTextAndUrl(
      t('Website User Guide - News Sharing'),
      Url::fromUri('https://sdssuserguide-prod.stanford.edu/configure/content-importers/news-sharing')
    )->toString();

    $form['urls'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Source URLs'),
      '#description' => $this->t('Enter the full source URL of the SDSS site to pull from, including the terms. More information available at @link.', [
        '@link' => $user_guide_link,
      ]),
      '#default_value' => implode(PHP_EOL, $urls),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
    $status = $form_state->getValue('status');
    if ($status) {
      $urls = array_filter(explode(PHP_EOL, str_replace("\r", '', $form_state->getValue('urls'))));
      foreach ($urls as &$url) {
        $url = trim($url);
        if (!UrlHelper::isValid($url, TRUE)) {
          $form_state->setError($form['urls'], $this->t('@url is not a valid url.', ['@url' => $url]));
          return;
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $urls = array_filter(explode(PHP_EOL, str_replace("\r", '', $form_state->getValue('urls'))));
    $this->configFactory()
      ->getEditable('sdss_news_sharing.settings')
      ->set('urls', $urls)
      ->set('status', $form_state->getValue('status'))
      ->save();
    parent::submitForm($form, $form_state);
    Cache::invalidateTags(['migration_plugins']);
  }

}
