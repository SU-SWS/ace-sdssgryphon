<?php

// @todo Turn this into an updatedb hook.

echo "Running script...";

// Get all stanford_news nodes and loop through them.
$entity_type_manager = \Drupal::entityTypeManager();
$stanford_news_nodes = $entity_type_manager->getStorage('node')->loadByProperties(['type' => 'stanford_news']);
$node_count = 0;
foreach($stanford_news_nodes as $stanford_news_node) {
  if($node_count < 3) {
    // Pull all paragraphs off the news nodes.
    $components = $stanford_news_node->get('su_news_components');
    // Create empty array to store paragraphs that are not in a layout section.
    $unsectioned_paragraphs = [];

    // Only continue if the node has components.
    if(count($components) > 0 ) {

      // Loop through the components.
      foreach($components as $component) {
        $paragraph = $entity_type_manager->getStorage('paragraph')->loadRevision($component->getValue()['target_revision_id']);
        $parent_uuid = $paragraph->getBehaviorSetting('layout_paragraphs', 'parent_uuid');
        $paragraph_type = $paragraph->getParagraphType()->id();

        // If the paragraph is already in a layout or the paragraph type IS a
        // layout section, do nothing.
        if(
          !is_null($parent_uuid)
          || $paragraph_type == 'stanford_layout'
        ) continue;

        // Add paragraph to the unsectioned_paragraphs array.
        $unsectioned_paragraphs[] = $paragraph;
      }

      // Check if there are unsectioned paragraphs.
      if(count($unsectioned_paragraphs) > 0) {
        var_dump("Node id: " . $stanford_news_node->id() .
          " has " . count($unsectioned_paragraphs) .
          " unsectioned paragraphs");

        // Create a stanford_layout paragraph to move un-sectioned components into.
        $new_paragraph_section = $entity_type_manager->getStorage('paragraph')->create([
          'type' => 'stanford_layout', // Enter your paragraph bundle type here.
        ]);
        $new_paragraph_section->setBehaviorSettings('layout_paragraphs',
          [
            'layout' => 'layout_paragraphs_sdss_1_column',
            'parent_uuid' => NULL,
            'region' => NULL,
          ]);
        $new_paragraph_section->save();

        // Loop through unsectioned paragraphs.
        foreach($unsectioned_paragraphs as $unsectioned_paragraph) {
          // Assign the paragraph to the newly created section paragraph.
          $unsectioned_paragraph->setBehaviorSettings('layout_paragraphs',
            [
              'region' => 'main',
              'parent_uuid' => $new_paragraph_section->uuid(),
            ]);
          $unsectioned_paragraph->save();
        }

        // Add the layout section paragraph to the node as the first reference
        // item and save.
        $stanford_news_node->su_news_components[] = [
          'target_id' => $new_paragraph_section->id(),
          'target_revision_id' => $new_paragraph_section->getRevisionId(),
        ];
        $stanford_news_node->save();
      }
    }
  }
  $node_count++;
}

?>
