<?php

namespace Drupal\sdss_person_title_override\Service;

use Drupal\node\NodeInterface;

/**
 * Service for retrieving display titles for stanford_person nodes.
 */
class SdssPersonTitleService {

  /**
   * Get the display full title for a stanford_person node.
   *
   * @param \Drupal\node\NodeInterface $node
   *   The node entity.
   *
   * @return string
   *   The display full title, or empty string if none set.
   *
   * @throws \LogicException
   *   If required fields are missing or node is not stanford_person.
   */
  public function getDisplayFullTitle(NodeInterface $node) {
    if ($node->bundle() !== 'stanford_person') {
      throw new \LogicException('Node is not of type stanford_person.');
    }
    if (!$node->hasField('su_sdss_person_alt_title') || !$node->hasField('su_person_full_title')) {
      throw new \LogicException('Required fields su_sdss_person_alt_title or su_person_full_title are missing.');
    }
    $alt = $node->get('su_sdss_person_alt_title')->value;
    $full = $node->get('su_person_full_title')->value;
    return !empty($alt) ? $alt : (!empty($full) ? $full : '');
  }

  /**
   * Get the display short title for a stanford_person node.
   *
   * @param \Drupal\node\NodeInterface $node
   *   The node entity.
   *
   * @return string
   *   The display short title, or empty string if none set.
   *
   * @throws \LogicException
   *   If required fields are missing or node is not stanford_person.
   */
  public function getDisplayShortTitle(NodeInterface $node) {
    if ($node->bundle() !== 'stanford_person') {
      throw new \LogicException('Node is not of type stanford_person.');
    }
    if (!$node->hasField('su_sdss_person_alt_title') || !$node->hasField('su_person_short_title')) {
      throw new \LogicException('Required fields su_sdss_person_alt_title or su_person_short_title are missing.');
    }
    $alt = $node->get('su_sdss_person_alt_title')->value;
    $short = $node->get('su_person_short_title')->value;
    return !empty($alt) ? $alt : (!empty($short) ? $short : '');
  }
}
