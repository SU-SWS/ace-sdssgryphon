<?php

namespace Drupal\Tests\sdss_profile\Unit\Plugin\HelpSection;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\Utility\LinkGeneratorInterface;
use Drupal\sdss_profile\Plugin\HelpSection\ProfileResourceSection;
use Drupal\Tests\UnitTestCase;

/**
 * Class ProfileResourceSectionTest
 *
 * @group sdss_profile
 * @coversDefaultClass \Drupal\sdss_profile\Plugin\HelpSection\ProfileResourceSection
 */
class ProfileResourceSectionTest extends UnitTestCase {

  /**
   * {@inheritDoc}
   */
  public function setup(): void {
    parent::setUp();
    $container = new ContainerBuilder();
    $container->set('string_translation', $this->getStringTranslationStub());

    $container->set('link_generator', $this->createMock(LinkGeneratorInterface::class));
    \Drupal::setContainer($container);
  }

  /**
   * Test resource topics exist.
   */
  public function testResourceSections() {
    $plugin = new ProfileResourceSection([], '', []);
    $topics = $plugin->listTopics();
    $this->assertCount(3, $topics);
  }

}
