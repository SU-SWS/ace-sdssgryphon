<?php

namespace Drupal\sdss_profile_helper\Plugin\views\row;

use Drupal\views\Plugin\views\row\EntityRow;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a row plugin that switches view mode based on grid/list toggle.
 *
 * @ViewsRow(
 *   id = "grid_list_entity_row",
 *   deriver = "Drupal\sdss_profile_helper\Plugin\Derivative\GridListEntityRow",
 *   title = @Translation("Rendered entity (Grid/List Switch)"),
 *   help = @Translation("Renders an entity using a view mode, switching between two modes based on a grid/list toggle.")
 * )
 */
class EntityRowGridList extends EntityRow {

	/**
	 * {@inheritdoc}
	 */
	public function defineOptions() {
		$options = parent::defineOptions();
		$options['grid_view_mode'] = ['default' => 'grid'];
		$options['list_view_mode'] = ['default' => 'list'];
		return $options;
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildOptionsForm(&$form, FormStateInterface $form_state) {
		parent::buildOptionsForm($form, $form_state);
    // Only build options if entityTypeId is set.
    $options = ['default' => $this->t('Default')];
    if (!empty($this->entityTypeId)) {
      $options = \Drupal::service('entity_display.repository')->getViewModeOptions($this->entityTypeId);
    }

    $form['grid_view_mode'] = [
      '#type' => 'select',
      '#title' => $this->t('Grid view mode'),
      '#options' => $options,
      '#default_value' => $this->options['grid_view_mode'],
      '#description' => $this->t('View mode to use when grid is selected.'),
    ];
    $form['list_view_mode'] = [
      '#type' => 'select',
      '#title' => $this->t('List view mode'),
      '#options' => $options,
      '#default_value' => $this->options['list_view_mode'],
      '#description' => $this->t('View mode to use when list is selected.'),
    ];
	}

  /**
	 * {@inheritdoc}
	 */
  public function preRender($result) {
    // Override the view mode based on the toggle before rendering.
    $mode = $this->getViewMode();
    // $this->options['view_mode'] = $mode;
    $this->view->rowPlugin->options['view_mode'] = $mode;
    parent::preRender($result);
  }  

	protected function getViewMode() {
		// Determine which view mode to use based on the toggle.
		$mode = \Drupal::request()->query->get('grid_list_display_mode') ?? 'grid';
		if ($mode === 'list') {
			return $this->options['list_view_mode'];
		}
		return $this->options['grid_view_mode'];
	}

}
