<?php

$node_storage = \Drupal::entityTypeManager()->getStorage('node');
$row_storage = \Drupal::entityTypeManager()->getStorage('paragraph_row');
$paragraph_storage = \Drupal::entityTypeManager()->getStorage('paragraph');

// $node_ids = array_splice($sandbox['nids'], 0, 25);
$node_ids = [16];

$nodes = $node_storage->loadMultiple($node_ids);
foreach ($nodes as $node) {
  /** @var \Drupal\Core\Field\EntityReferenceFieldItemListInterface $row */
  foreach ($node->get('su_page_components') as $paragraph) {
    $section_entity = $paragraph_storage->loadRevision($paragraph->get('target_revision_id')
      ->getString());


    if($section_entity->getParagraphType()->id() == 'stanford_layout') {
      $behavior_settings = $section_entity->getAllBehaviorSettings();
      var_dump($behavior_settings);
      // set all to blue just to test
      $behavior_settings['layout_paragraphs']['config']['bg_color'] = 'blue';
      var_dump($behavior_settings);
      // var_dump(get_class_methods($section_entity));
      $section_entity->setAllBehaviorSettings($behavior_settings);
      $section_entity->save();
    }
    //var_dump($paragraph->getBehaviorSetting('layout_paragraphs', 'parent_uuid'));
    //  var_dump($paragraph->get('target_revision_id'));


    // $number_of_items = min($row_entity->get('su_page_components')->count(), 3);
    // $layout_id = "layout_paragraphs_sdss_{$number_of_items}_column";

    // $new_row_entity = $paragraph_storage->create(['type' => 'stanford_layout']);
    // $new_row_entity->setBehaviorSettings('layout_paragraphs', [
    //   'layout' => $layout_id,
    //   'config' => ['label' => ''],
    //   'parent_uuid' => NULL,
    //   'region' => NULL,
    // ]);
    // $new_row_entity->setParentEntity($node, 'su_page_components');
    // $new_row_entity->save();

    // $new_components[] = [
    //   'target_id' => $new_row_entity->id(),
    //   'entity' => $new_row_entity,
    // ];

    /** @var \Drupal\Core\Field\EntityReferenceFieldItemListInterface $row_item */
    // foreach ($row_entity->get('su_page_components') as $delta => $row_item) {
    //   /** @var \Drupal\paragraphs\ParagraphInterface $paragraph */
    //   $paragraph = $paragraph_storage->loadRevision($row_item->get('target_revision_id')
    //     ->getString());
    //   $behaviors = $paragraph->getAllBehaviorSettings();
    //   unset($behaviors['react']);
    //   $behaviors['layout_paragraphs'] = [
    //     'parent_uuid' => $new_row_entity->uuid(),
    //     'region' => _sdss_profile_update_9005_get_item_region($delta, $layout_id),
    //   ];
    //   $new_paragraph = $paragraph->createDuplicate();
    //   $new_paragraph->enforceIsNew();
    //   $new_paragraph->setParentEntity($node, 'su_page_components');
    //   $new_paragraph->setAllBehaviorSettings($behaviors);

    //   $new_components[] = [
    //     'target_id' => $new_paragraph->id(),
    //     'entity' => $new_paragraph,
    //   ];
    // }
  }
}


/**
 * Migrate Basic Page react paragraphs to layout paragraphs.
 */
// function sdss_profile_update_9005(&$sandbox)
// {
//   $node_storage = \Drupal::entityTypeManager()
//     ->getStorage('node');
//   if (!isset($sandbox['count'])) {
//     $nids = $node_storage->getQuery()
//       ->accessCheck(FALSE)
//       ->condition('type', 'stanford_page')
//       ->sort('created')
//       ->execute();
//     $sandbox['nids'] = $nids;
//     $sandbox['count'] = count($sandbox['nids']);
//   }
//   drupal_static_reset();
//   $row_storage = \Drupal::entityTypeManager()->getStorage('paragraph_row');
//   $paragraph_storage = \Drupal::entityTypeManager()->getStorage('paragraph');
//   $node_ids = array_splice($sandbox['nids'], 0, 25);

//   /** @var \Drupal\node\NodeInterface[] $nodes */
//   $nodes = $node_storage->loadMultiple($node_ids);
//   $delete_entities = [];
//   foreach ($nodes as $node) {
//     $changed_time = $node->getChangedTime();
//     $new_components = [];
//     /** @var \Drupal\Core\Field\EntityReferenceFieldItemListInterface $row */
//     foreach ($node->get('su_page_components') as $row) {
//       $row_entity = $row_storage->loadRevision($row->get('target_revision_id')
//         ->getString());

//       $delete_entities[] = $row_entity;
//       $number_of_items = min($row_entity->get('su_page_components')->count(), 3);
//       $layout_id = "layout_paragraphs_sdss_{$number_of_items}_column";

//       $new_row_entity = $paragraph_storage->create(['type' => 'stanford_layout']);
//       $new_row_entity->setBehaviorSettings('layout_paragraphs', [
//         'layout' => $layout_id,
//         'config' => ['label' => ''],
//         'parent_uuid' => NULL,
//         'region' => NULL,
//       ]);
//       $new_row_entity->setParentEntity($node, 'su_page_components');
//       $new_row_entity->save();

//       $new_components[] = [
//         'target_id' => $new_row_entity->id(),
//         'entity' => $new_row_entity,
//       ];

//       /** @var \Drupal\Core\Field\EntityReferenceFieldItemListInterface $row_item */
//       foreach ($row_entity->get('su_page_components') as $delta => $row_item) {
//         /** @var \Drupal\paragraphs\ParagraphInterface $paragraph */
//         $paragraph = $paragraph_storage->loadRevision($row_item->get('target_revision_id')
//           ->getString());
//         $behaviors = $paragraph->getAllBehaviorSettings();
//         unset($behaviors['react']);
//         $behaviors['layout_paragraphs'] = [
//           'parent_uuid' => $new_row_entity->uuid(),
//           'region' => _sdss_profile_update_9005_get_item_region($delta, $layout_id),
//         ];
//         $new_paragraph = $paragraph->createDuplicate();
//         $new_paragraph->enforceIsNew();
//         $new_paragraph->setParentEntity($node, 'su_page_components');
//         $new_paragraph->setAllBehaviorSettings($behaviors);
//         $new_paragraph->save();

//         $new_components[] = [
//           'target_id' => $new_paragraph->id(),
//           'entity' => $new_paragraph,
//         ];
//         $delete_entities[] = $paragraph;
//       }
//     }
//     $node->set('su_page_components', $new_components)->save();
//     _sdss_profile_reset_node_changed_time($node, $changed_time);
//   }
//   foreach ($delete_entities as $entity) {
//     $entity->delete();
//   }
//   $sandbox['#finished'] = empty($sandbox['nids']) ? 1 : ($sandbox['count'] - count($sandbox['nids'])) / $sandbox['count'];
// }
