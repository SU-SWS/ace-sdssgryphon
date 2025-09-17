<?php

namespace Drupal\sdss_profile\EventSubscriber;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Layout\LayoutPluginManagerInterface;
use Drupal\layout_paragraphs\Event\LayoutParagraphsAllowedTypesEvent;
use Drupal\path_alias\AliasManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Custom restrictions on paragraph types.
 * 
 * Paragraph type restrictions based on layouts and regions belong in the layout
 * definitions in sdss_layout_paragraphs. This implementation restricts based
 * on other contexts.
 */
class ParagraphRestrictionsSubscriber implements EventSubscriberInterface {

  /**
   * The layout manager.
   *
   * @var \Drupal\Core\Layout\LayoutPluginManagerInterface
   */
  protected $layoutManager;
  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The path alias manager.
   *
   * @var \Drupal\path_alias\AliasManagerInterface
   */
  protected $aliasManager;

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    return [
      LayoutParagraphsAllowedTypesEvent::EVENT_NAME => 'layoutParagraphsAllowedTypes',
    ];
  }

  /**
   * Constructs a new ParagraphRestrictionsSubscriber.
   *
   * @param \Drupal\Core\Layout\LayoutPluginManagerInterface $layout_manager
   *   The layout manager.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\Core\Path\AliasManagerInterface $alias_manager
   *   The path alias manager.
   */
  public function __construct(
    LayoutPluginManagerInterface $layout_manager,
    EntityTypeManagerInterface $entity_type_manager,
    ConfigFactoryInterface $config_factory,
    AliasManagerInterface $alias_manager
  ) {
    $this->layoutManager = $layout_manager;
    $this->entityTypeManager = $entity_type_manager;
    $this->configFactory = $config_factory;
    $this->aliasManager = $alias_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create($container) {
    return new static(
      $container->get('plugin.manager.core.layout'),
      $container->get('entity_type.manager'),
      $container->get('config.factory'),
      $container->get('path_alias.manager')
    );
  }

  /**
   * Adjust the paragraphs allowed in the layout paragraphs widget.
   *
   * @param \Drupal\layout_paragraphs\Event\LayoutParagraphsAllowedTypesEvent $event
   *   Layout paragraphs event.
   */
  public function layoutParagraphsAllowedTypes(LayoutParagraphsAllowedTypesEvent $event): void {
    // Paragraph types only allowed on the Sustainability site home page.
    $sustainability_home_paragraphs = [
      'su_pinned_background_section',
      'su_image_cta',
      'su_departments_slider',
      'su_horizontal_bar',
      'su_header_with_link',
    ];

    // Unset paragraph types if not on the Sustainability site.
    if ($this->isSustainabilitySite() === FALSE) {
      $types = $this->unsetParagraphTypes($event->getTypes(), $sustainability_home_paragraphs);
      $event->setTypes($types);
      return;
    }
    
    // Unset paragraph types if not on the home page.
    if ($this->isFrontPage($event->getParentUuid()) === FALSE) {
      $types = $this->unsetParagraphTypes($event->getTypes(), $sustainability_home_paragraphs);
      $event->setTypes($types);
      return;
    }
  }

  /**
   * Removes specified paragraph types from the allowed types array.
   *
   * @param array $types
   *   The allowed paragraph types.
   * @param array $unset_paragraphs
   *   The paragraph type machine names to remove.
   *
   * @return array
   *   The filtered allowed paragraph types.
   */
  protected function unsetParagraphTypes(array $types, array $unset_paragraphs): array {
    foreach ($unset_paragraphs as $paragraph_type) {
      unset($types[$paragraph_type]);
    }
    return $types;
  }

  /**
   * Checks if the current site is the sustainability site.
   *
   * @return bool
   *   TRUE if the site is 'sustainability', FALSE otherwise.
   */
  protected function isSustainabilitySite(): bool {
    $site_path = \Drupal::getContainer()->getParameter('site.path');
    $site_name = basename($site_path);
    return $site_name === 'sustainability';
  }
  
  /**
   * Checks if the parent node of the given paragraph UUID is the front page
   * node.
   *
   * @param string $parent_uuid
   *   The UUID of the parent paragraph entity.
   *
   * @return bool
   *   TRUE if the parent node is the front page node, FALSE otherwise.
   */
  protected function isFrontPage(string $parent_uuid): bool {
    $paragraph_storage = $this->entityTypeManager->getStorage('paragraph');
    $paragraphs = $paragraph_storage->loadByProperties(['uuid' => $parent_uuid]);
    $paragraph = reset($paragraphs);

    $node_id = NULL;
    if (
      $paragraph && 
      $paragraph->getParentEntity() && 
      $paragraph->getParentEntity()->getEntityTypeId() === 'node'
    ) {
      $node = $paragraph->getParentEntity();
      $node_id = $node->id();
    }

    $front_nid = NULL;
    $front = $this->configFactory->get('system.site')->get('page.front');
    if ($front && preg_match('/^\/?node\/(\d+)$/', $front, $matches)) {
      $front_nid = $matches[1];
    } else {
      $system_path = $this->aliasManager->getPathByAlias($front);
      if (preg_match('/^\/?node\/(\d+)$/', $system_path, $matches)) {
        $front_nid = $matches[1];
      }
    }

    if (!isset($node_id) || !isset($front_nid) || $node_id != $front_nid) {
      return FALSE;
    }
    return TRUE;
  }

}
