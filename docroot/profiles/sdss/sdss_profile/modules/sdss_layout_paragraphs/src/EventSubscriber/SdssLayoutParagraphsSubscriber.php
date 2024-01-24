<?php

namespace Drupal\sdss_layout_paragraphs\EventSubscriber;

use Drupal\Core\Layout\LayoutPluginManagerInterface;
use Drupal\layout_paragraphs\Event\LayoutParagraphsAllowedTypesEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * SDSS layout paragraphs event subscriber.
 */
class SdssLayoutParagraphsSubscriber implements EventSubscriberInterface
{

  /**
   * The layout manager.
   *
   * @var \Drupal\Core\Layout\LayoutPluginManagerInterface
   */
  protected $layoutManager;

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array
  {
    return [
      LayoutParagraphsAllowedTypesEvent::EVENT_NAME => 'layoutParagraphsAllowedTypes',
    ];
  }

  /**
   * Event subscriber constructor.
   *
   * @param \Drupal\Core\Layout\LayoutPluginManagerInterface $layout_manager
   *   The layout manager.
   */
  public function __construct(LayoutPluginManagerInterface $layout_manager)
  {
    $this->layoutManager = $layout_manager;
  }

  /**
   * Adjust the paragraphs allowed in the layout paragraphs widget.
   *
   * @param \Drupal\layout_paragraphs\Event\LayoutParagraphsAllowedTypesEvent $event
   *   Layout paragraphs event.
   */
  public function layoutParagraphsAllowedTypes(LayoutParagraphsAllowedTypesEvent $event): void
  {
    $parent_component = $event->getLayout()
      ->getComponentByUuid($event->getParentUuid());

    // If adding a new layout, it won't have a parent.
    if ($parent_component) {

      // Would like to improve this and put in some kind of settings.
      // Hard coding here for now.
      $restrict_paragraphs = [
        'layout_paragraphs_sdss_2_column' => [
          'left' => [
            'sdss_spotlight',
            'stanford_banner',
            'stanford_gallery'
          ],
          'right' => [
            'sdss_spotlight',
            'stanford_banner',
            'stanford_gallery'
          ]
        ],
        'layout_paragraphs_sdss_3_column' => [
          'left' => [
            'sdss_spotlight',
            'stanford_banner',
            'stanford_gallery'
          ],
          'main' => [
            'sdss_spotlight',
            'stanford_banner',
            'stanford_gallery'
          ],
          'right' => [
            'sdss_spotlight',
            'stanford_banner',
            'stanford_gallery'
          ]
        ],
      ];


      $layout_settings = $parent_component->getSettings();

      if (
        array_key_exists($layout_settings['layout'], $restrict_paragraphs)
        && array_key_exists($event->getRegion(), $restrict_paragraphs[$layout_settings['layout']])
      ) {
        $types = $event->getTypes();
        foreach ($restrict_paragraphs[$layout_settings['layout']][$event->getRegion()] as $paragraph_type) {
          if (isset($types[$paragraph_type])) {
            unset($types[$paragraph_type]);
          }
        }
        $event->setTypes($types);
      }
    }
  }
}
