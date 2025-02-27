<?php

use Faker\Factory;
use Drupal\layout_builder\Section;
use Drupal\layout_builder\SectionComponent;

/**
 * Test the events functionality.
 *
 * @group content
 * @group events
 */
class EventsCest {

  /**
   * Faker generator.
   *
   * @var \Faker\Generator $faker
   */
  protected $faker;

  /**
   * EventsCest constructor.
   */
  public function __construct() {
    $this->faker = Factory::create();
  }

  /**
   * Mini calendar display.
   *
   * @group mini_calendar
   */
  public function testMiniCalendar(FunctionalTester $I) {
    $events = [];
    $current_month = (int) date('n');
    for ($i = $current_month; $i < $current_month + 12; $i++) {
      // Use the 10th and 20th of the month to ensure we have events with
      // double-digit days and to avoid the previous/next month days displaying.
      $begin = mktime(0, 0, 0, $i, 10);
      $end = mktime(0, 0, 0, $i, 20);
      $start_time = rand($begin, $end);
      $events[$i] = $I->createEntity([
        'type' => 'stanford_event',
        'title' => $this->faker->words(3, TRUE),
        'su_event_date_time' => [
          'value' => $start_time,
          'end_value' => $start_time,
        ],
      ]);
    }

    /** @var \Drupal\Component\Uuid\UuidInterface $uuid_service */
    $uuid_service = \Drupal::service('uuid');
    $components = [
      new SectionComponent($uuid_service->generate(), 'main', [
        'id' => 'react_component:mini_calendar',
        'label' => 'Mini Calendar',
        'label_display' => 'hidden',
        'provider' => 'pdb_react',
      ]),
    ];
    $layout = [
      ['section' => new Section('jumpstart_ui_one_column', [], $components)],
    ];

    $node = $I->createEntity([
      'type' => 'stanford_page',
      'title' => 'Mini Calendar Page',
      'layout_builder__layout' => $layout,
    ]);
    $I->amOnPage($node->toUrl()->toString());
    $I->canSee('Mini Calendar', 'h2');
    $I->canSee(date('F Y'), '.mini-calendar');

    foreach ($events as $event) {
      $start_timestamp = $event->get('su_event_date_time')
        ->get(0)
        ->get('value')
        ->getString();

      $button_label = date('M jS Y', $start_timestamp);

      $I->waitForElementClickable("button[aria-label='$button_label']");
      $I->wait(1);
      $I->click("[aria-label='$button_label']");
      $I->waitForText($event->label(), 5);
      $I->canSee($event->label(), 'dialog');
      $I->click('Close Dialog');
      $I->click('Next Month');
    }
  }
}
