<?php

namespace Drupal\Tests\sdss_profile\Unit\Plugin\HelpSection;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\Utility\LinkGeneratorInterface;
use Drupal\sdss_profile\Plugin\HelpSection\ProfileHelpSection;
use Drupal\Tests\UnitTestCase;

/**
 * Class ProfileHelpSectionTest
 *
 * @group sdss_profile
 * @coversDefaultClass \Drupal\sdss_profile\Plugin\HelpSection\ProfileHelpSection
 */
class ProfileHelpSectionTest extends UnitTestCase {

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
   * Test help topics exist.
   */
  public function testHelpSections() {
    $plugin = new ProfileHelpSection([], '', []);
    $topics = $plugin->listTopics();
    $this->assertCount(6, $topics);
  }

}
