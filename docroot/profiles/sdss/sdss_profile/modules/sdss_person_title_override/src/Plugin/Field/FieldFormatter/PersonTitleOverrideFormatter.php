<?php

namespace Drupal\sdss_person_title_override\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Field\Attribute\FieldFormatter;
use Drupal\sdss_person_title_override\Service\SdssPersonTitleService;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'person_title_override' formatter.
 */
#[FieldFormatter(
  id: "person_title_override",
  label: new TranslatableMarkup("Person Title Override"),
  field_types: ["string", "string_long", "text", "text_long"],
)]

/**
 * Provides a field formatter for Stanford Person title fields overrides.
 *
 * This formatter uses dependency injection for all services and is intended
 * only for the su_person_full_title and su_person_short_title fields on the
 * stanford_person content type. If used elsewhere, a warning is shown to
 * editors/admins and a watchdog log entry is created.
 *
 * @package Drupal\sdss_person_title_override\Plugin\Field\FieldFormatter
 */
class PersonTitleOverrideFormatter extends FormatterBase {

  /**
   * The SDSS Person Title override service.
   *
   * @var \Drupal\sdss_person_title_override\Service\SdssPersonTitleService
   */
  protected $personTitleService;

  /**
   * The messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * The logger channel.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * {@inheritdoc}
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   The service container.
   * @param array $configuration
   *   The plugin configuration.
   * @param string $plugin_id
   *   The plugin ID.
   * @param mixed $plugin_definition
   *   The plugin definition.
   *
   * @return static
   *   Returns an instance of this formatter with dependencies injected.
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['label'],
      $configuration['view_mode'],
      $configuration['third_party_settings'],
      $container->get('sdss_person_title_override.sdss_person_title'),
      $container->get('messenger'),
      $container->get('current_user'),
      $container->get('logger.factory')->get('sdss_person_title_override')
    );
  }

  /**
   * Constructs a PersonTitleOverrideFormatter object with dependencies.
   *
   * @param string $plugin_id
   *   The plugin ID for the formatter.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   The definition of the field to which the formatter is associated.
   * @param array $settings
   *   The formatter settings.
   * @param string $label
   *   The formatter label display setting.
   * @param string $view_mode
   *   The view mode.
   * @param array $third_party_settings
   *   Any third party settings.
   * @param object $personTitleService
   *   The SDSS Person Title override service.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger service.
   * @param \Drupal\Core\Session\AccountInterface $currentUser
   *   The current user.
   * @param \Psr\Log\LoggerInterface $logger
   *   The logger channel.
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, $label, $view_mode, array $third_party_settings, SdssPersonTitleService $personTitleService, MessengerInterface $messenger, AccountInterface $currentUser, LoggerInterface $logger) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings);
    $this->personTitleService = $personTitleService;
    $this->messenger = $messenger;
    $this->currentUser = $currentUser;
    $this->logger = $logger;
  }

  /**
   * {@inheritdoc}
   *
   * Renders the field value using override logic for the two supported fields.
   *
   * If used on any other field, shows a warning to editors/admins and logs a
   * warning. Falls back to the default field value.
   *
   * @param \Drupal\Core\Field\FieldItemListInterface $items
   *   The field values to render.
   * @param string $langcode
   *   The language code.
   *
   * @return array
   *   A renderable array for the field output.
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    $entity = $items->getEntity();
    $field_name = $items->getFieldDefinition()->getName();
    $value = '';
    if ($field_name === 'su_person_full_title') {
      $value = $this->personTitleService->getDisplayFullTitle($entity);
    }
    elseif ($field_name === 'su_person_short_title') {
      $value = $this->personTitleService->getDisplayShortTitle($entity);
    }
    else {
      if (
        $this->currentUser->hasPermission('edit any stanford_person content') ||
        $this->currentUser->hasPermission('administer nodes')
      ) {
        $this->messenger->addWarning($this->t('PersonTitleOverrideFormatter: This formatter is intended for Full or Short title fields on the Stanford Person Content Type. Default field value will be used for field: @field.', ['@field' => $field_name]));
      }
      $this->logger->warning('PersonTitleOverrideFormatter used on field: @field (should only be used on su_person_full_title or su_person_short_title).', ['@field' => $field_name]);
      $value = $items[0]->value ?? '';
    }
    $elements[0] = [
      '#markup' => $value,
    ];
    return $elements;
  }

}
