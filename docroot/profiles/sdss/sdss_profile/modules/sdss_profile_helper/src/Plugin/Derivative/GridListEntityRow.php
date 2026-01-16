<?php

namespace Drupal\sdss_profile_helper\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;

/**
 * Provides a derivative for the grid/list entity row plugin for nodes only.
 */
class GridListEntityRow extends DeriverBase {

	/**
	 * {@inheritdoc}
	 */
	public function getDerivativeDefinitions($base_plugin_definition) {
		$derivatives = [];
		// Only for nodes.
		$derivatives['entity:node_grid_list'] = $base_plugin_definition;
		$derivatives['entity:node_grid_list']['title'] = t('Rendered entity (Grid/List Switch)');
		$derivatives['entity:node_grid_list']['help'] = t('Renders a node using a view mode, switching between two modes based on a grid/list toggle.');
		$derivatives['entity:node_grid_list']['entity_type'] = 'node';
		return $derivatives;
	}
}
