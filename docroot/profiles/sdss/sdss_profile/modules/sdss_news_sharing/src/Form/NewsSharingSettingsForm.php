<?php

namespace Drupal\sdss_news_sharing\Form;

use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use GuzzleHttp\ClientInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class EventsImporterForm.
 */
class NewsSharingSettingsForm extends ConfigFormBase {

  /**
   * Entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Http client service.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $guzzle;

  /**
   * Cache service.
   *
   * @var \Drupal\Core\Cache\CacheBackendInterface
   */
  protected $cache;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('entity_type.manager'),
      $container->get('http_client'),
      $container->get('cache.default')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function __construct(ConfigFactoryInterface $config_factory, EntityTypeManagerInterface $entity_type_manager, ClientInterface $client, CacheBackendInterface $cache) {
    parent::__construct($config_factory);
    $this->entityTypeManager = $entity_type_manager;
    $this->guzzle = $client;
    $this->cache = $cache;
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
    $url = $this->config('sdss_news_sharing.settings')->get('url') ?: [];

    // $form['extra_urls'] = [
    //   '#type' => 'textarea',
    //   '#title' => $this->t('Event URLs'),
    //   '#description' => $this->t('Enter the full url if available or use the fields below.'),
    //   '#default_value' => implode(PHP_EOL, $this->getExtraUrls($urls)),
    // ];

    // $form['add_one'] = [
    //   '#type' => 'submit',
    //   '#name' => 'add_one',
    //   '#value' => $this->t('Add one'),
    //   '#submit' => ['::addOne'],
    //   '#ajax' => [
    //     'callback' => '::addMoreCallback',
    //     'wrapper' => 'urls-wrapper',
    //   ],
    // ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);

    // $urls = array_filter(explode(PHP_EOL, str_replace("\r", '', $form_state->getValue('extra_urls'))));
    // foreach ($urls as &$url) {
    //   $url = trim($url);
    //   $this->validateUrl($url, $form, $form_state);
    // }

    // foreach ($form_state->getValue('url_set') as $url_settings) {
    //   $urls[] = $this->getFullUrl($url_settings);
    // }
    // asort($urls);
    // $urls = array_values(array_unique(array_filter($urls)));
    // $form_state->setValue('urls', $urls);
  }

  /**
   * Validate that the user entered values are xml feeds from stanford events.
   *
   * @param string $url
   *   Url to events-legacy.stanford.edu.
   * @param array $form
   *   Complete form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Current form state.
   */
  protected function validateUrl($url, array &$form, FormStateInterface $form_state) {
    if (!UrlHelper::isValid($url, TRUE)) {
      $form_state->setError($form['extra_urls'], $this->t('@url is not a valid url.', ['@url' => $url]));
      return;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // $this->configFactory()
    //   ->getEditable('hs_events_importer.settings')
    //   ->set('urls', $form_state->getValue('urls'))
    //   ->save();
    // parent::submitForm($form, $form_state);
    // Cache::invalidateTags(['migration_plugins']);

    // // Add permission to execute importer.
    // $role = $this->entityTypeManager->getStorage('user_role')
    //   ->load('site_manager');
    // if ($role) {
    //   $role->grantPermission('import hs_events_importer migration');
    //   $role->save();
    // }
  }

  /**
   * Parse the url and build an array with the query parts.
   *
   * @param string $url
   *   Events-legacy.stanford.edu url.
   *
   * @return array
   *   Keyed array of the query parameters for the url.
   */
  protected function getUrlDefaults($url) {
    // Break up the URL to get at the query strings.
    $parts = UrlHelper::parse($url);
    $parsed = [];
    // Pull apart the query strings and set them to keys for easy use.
    if (isset($parts['query'])) {
      $parsed = $parts['query'];
      $keys = array_keys($parts['query']);
      $parsed['type'] = array_shift($keys);
      $parsed['org_status'] = array_pop($keys);
    }

    return $parsed;
  }

}
