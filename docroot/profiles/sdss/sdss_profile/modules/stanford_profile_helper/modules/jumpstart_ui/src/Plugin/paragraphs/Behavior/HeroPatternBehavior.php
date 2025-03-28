<?php

namespace Drupal\jumpstart_ui\Plugin\paragraphs\Behavior;

use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\paragraphs\ParagraphInterface;
use Drupal\paragraphs\ParagraphsBehaviorBase;
use Drupal\paragraphs\ParagraphsTypeInterface;

/**
 * Hero Pattern Behavior.
 *
 * @ParagraphsBehavior(
 *   id = "hero_pattern",
 *   label = @Translation("Hero Pattern"),
 *   description = @Translation("Display options for the hero pattern
 *   paragraph.")
 * )
 */
class HeroPatternBehavior extends ParagraphsBehaviorBase {

  /**
   * {@inheritDoc}
   */
  public function defaultConfiguration() {
    return [
      'overlay_position' => 'left',
      'heading' => 'h2',
      'hide_heading' => FALSE,
    ];
  }

  /**
   * {@inheritDoc}
   */
  public static function isApplicable(ParagraphsTypeInterface $paragraphs_type) {
    $display_storage = \Drupal::entityTypeManager()
      ->getStorage('entity_view_display');
    $display_ids = $display_storage->getQuery()
      ->accessCheck()
      ->condition('id', "paragraph.{$paragraphs_type->id()}.", 'STARTS_WITH')
      ->execute();

    // No displays exist for this paragraph for some reason, let's bail.
    if (empty($display_ids)) {
      return FALSE;
    }

    /** @var \Drupal\Core\Entity\Display\EntityViewDisplayInterface $display */
    foreach ($display_storage->loadMultiple($display_ids) as $display) {
      if ($layout = $display->getThirdPartySetting('ds', 'layout')) {
        if (!empty($layout['id']) && $layout['id'] == 'pattern_hero') {
          // If any of the displays for the given paragraph are configured to
          // display as the hero pattern, this behavior applicable and can be
          // enabled.
          return TRUE;
        }
      }
    }
    return FALSE;
  }

  /**
   * {@inheritDoc}
   */
  public function buildBehaviorForm(ParagraphInterface $paragraph, array &$form, FormStateInterface $form_state) {
    $form = parent::buildBehaviorForm($paragraph, $form, $form_state);
    $form['heading'] = [
      '#type' => 'select',
      '#title' => $this->t('Heading Level'),
      '#options' => [
        'h2' => 'H2',
        'h3' => 'H3',
        'h4' => 'H4',
        'div.su-font-splash' => $this->t('Splash Text'),
      ],
      '#default_value' => $paragraph->getBehaviorSetting('hero_pattern', 'heading', 'h2'),
    ];
    $form['hide_heading'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Visually Hide Heading'),
      '#default_value' => $paragraph->getBehaviorSetting('hero_pattern', 'hide_heading', FALSE),
    ];
    $form['overlay_position'] = [
      '#type' => 'select',
      '#title' => $this->t('Text Overlay Position'),
      '#description' => $this->t('Position the text over the image on larger screens'),
      '#default_value' => $paragraph->getBehaviorSetting('hero_pattern', 'overlay_position', 'left'),
      '#options' => [
        'left' => $this->t('Left'),
        'right' => $this->t('Right'),
      ],
    ];
    return $form;
  }

  /**
   * {@inheritDoc}
   */
  public function view(array &$build, ParagraphInterface $paragraph, EntityViewDisplayInterface $display, $view_mode) {
    // FYI: this adds the class one level above than the pattern template.
    $build['#attributes']['class'][] = 'overlay-' . $paragraph->getBehaviorSetting('hero_pattern', 'overlay_position', 'left');
  }

}
