<?php

$paragraph_type = \Drupal::entityTypeManager()->getStorage('paragraphs_type')->load('stanford_layout');
$behavior_plugin = $paragraph_type->getBehaviorPlugin('layout_paragraphs');
$configuration = [
  "enabled" => TRUE,
  "available_layouts" => [
    "layout_paragraphs_sdss_1_column" => "1 Column",
    "layout_paragraphs_sdss_2_column" => "2 Column",
    "layout_paragraphs_sdss_3_column" => "3 Column",
  ]
];
$behavior_plugin->setConfiguration($configuration);
$paragraph_type->save();
