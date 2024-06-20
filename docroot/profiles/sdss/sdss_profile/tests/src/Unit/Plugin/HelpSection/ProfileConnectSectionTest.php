<?php

namespace Drupal\Tests\sdss_profile\Unit\Plugin\HelpSection;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\Utility\LinkGeneratorInterface;
use Drupal\sdss_profile\Plugin\HelpSection\ProfileConnectSection;
use Drupal\Tests\UnitTestCase;

/**
 * Class ProfileConnectSectionTest
 *
 * @group sdss_profile
 * @coversDefaultClass \Drupal\sdss_profile\Plugin\HelpSection\ProfileConnectSection
 */
class ProfileConnectSectionTest extends UnitTestCase {

  /**
   * {@inheritDoc}
   */
  public function setup(): void {
    parent::setUp();
    $container = new ContainerBuilder();
    $container->set('string_translation', $this->getStringTranslationStub());

    $container->set('link_generator', $this->createMock(LinkGeneratorInterface::class));;
    \Drupal::setContainer($container);
  }

  /**
   * Test connection topics exist.
   */
  public function testConnectSections() {
    $plugin = new ProfileConnectSection([], '', []);
    $topics = $plugin->listTopics();
    $this->assertCount(1, $topics);
  }

}
