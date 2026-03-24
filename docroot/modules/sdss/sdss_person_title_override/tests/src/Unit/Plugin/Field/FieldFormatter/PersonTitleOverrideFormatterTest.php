<?php

declare(strict_types=1);

namespace Drupal\Tests\sdss_person_title_override\Unit\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\node\NodeInterface;
use Psr\Log\LoggerInterface;
use Drupal\sdss_person_title_override\Plugin\Field\FieldFormatter\PersonTitleOverrideFormatter;
use Drupal\sdss_person_title_override\Service\SdssPersonTitleService;
use PHPUnit\Framework\TestCase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Test the SDSS Person Title Override Field Formatter.
 *
 * @group sdss_person_title_override
 * @coversDefaultClass \Drupal\sdss_person_title_override\Plugin\Field\FieldFormatter\PersonTitleOverrideFormatter
 */
class PersonTitleOverrideFormatterTest extends TestCase {

  /**
   * Tests that the formatter uses the service for su_person_full_title fields.
   */
  public function testViewElementsFullTitleUsesService() {
    $entity = $this->createMock(NodeInterface::class);
    $service = $this->getMockBuilder(SdssPersonTitleService::class)
      ->disableOriginalConstructor()
      ->getMock();
    $service->expects($this->once())
      ->method('getDisplayFullTitle')
      ->with($entity)
      ->willReturn('Override Full Title');
    $messenger = $this->createMock(MessengerInterface::class);
    $currentUser = $this->createMock(AccountInterface::class);
    $logger = $this->createMock(LoggerInterface::class);

    $items = $this->createMock(FieldItemListInterface::class);
    $items->method('getEntity')->willReturn($entity);
    $items->method('getFieldDefinition')->willReturn($this->getFieldDefinition('su_person_full_title'));

    $formatter = $this->getFormatter($service, $messenger, $currentUser, $logger, 'su_person_full_title');
    $elements = $formatter->viewElements($items, 'en');
    $this->assertEquals(['#markup' => 'Override Full Title'], $elements[0]);
  }

  /**
   * Tests that the formatter uses the service for su_person_short_title fields.
   */
  public function testViewElementsShortTitleUsesService() {
    $entity = $this->createMock(NodeInterface::class);
    $service = $this->getMockBuilder(SdssPersonTitleService::class)
      ->disableOriginalConstructor()
      ->getMock();
    $service->expects($this->once())
      ->method('getDisplayShortTitle')
      ->with($entity)
      ->willReturn('Override Short Title');
    $messenger = $this->createMock(MessengerInterface::class);
    $currentUser = $this->createMock(AccountInterface::class);
    $logger = $this->createMock(LoggerInterface::class);

    $items = $this->createMock(FieldItemListInterface::class);
    $items->method('getEntity')->willReturn($entity);
    $items->method('getFieldDefinition')->willReturn($this->getFieldDefinition('su_person_short_title'));

    $formatter = $this->getFormatter($service, $messenger, $currentUser, $logger, 'su_person_short_title');
    $elements = $formatter->viewElements($items, 'en');
    $this->assertEquals(['#markup' => 'Override Short Title'], $elements[0]);
  }

  /**
   * Tests that the formatter shows a warning and logs for other fields, and
   * uses the default value.
   */
  public function testViewElementsOtherFieldShowsWarningAndLogs() {
    $entity = $this->createMock(NodeInterface::class);
    $service = $this->getMockBuilder(SdssPersonTitleService::class)
      ->disableOriginalConstructor()
      ->getMock();
    $messenger = $this->createMock(MessengerInterface::class);
    $currentUser = $this->createMock(AccountInterface::class);
    $logger = $this->createMock(LoggerInterface::class);

    $currentUser->method('hasPermission')->willReturn(true);
    $messenger->expects($this->once())->method('addWarning');
    $logger->expects($this->once())->method('warning');

    $items = $this->createMock(FieldItemListInterface::class);
    $items->method('getEntity')->willReturn($entity);
    $items->method('getFieldDefinition')->willReturn($this->getFieldDefinition('other_field'));

    $formatter = $this->getFormatter($service, $messenger, $currentUser, $logger, 'other_field');
    $elements = $formatter->viewElements($items, 'en');
    $this->assertEquals(['#markup' => ''], $elements[0]);
  }

  /**
   * Helper to instantiate the formatter with all dependencies.
   *
   * @param \Drupal\sdss_person_title_override\Service\SdssPersonTitleService $service
   *   The override service mock.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger mock.
   * @param \Drupal\Core\Session\AccountInterface $currentUser
   *   The current user mock.
   * @param \Psr\Log\LoggerInterface $logger
   *   The logger mock.
   * @param string $field_name
   *   The field name to use in the field definition mock.
   *
   * @return \Drupal\sdss_person_title_override\Plugin\Field\FieldFormatter\PersonTitleOverrideFormatter
   *   The formatter instance.
   */
  private function getFormatter($service, $messenger, $currentUser, $logger, $field_name) {
    $field_definition = $this->createMock(FieldDefinitionInterface::class);
    $field_definition->method('getName')->willReturn($field_name);
    $field_definition->method('getType')->willReturn('string');
    $formatter = $this->getMockBuilder(PersonTitleOverrideFormatter::class)
      ->setConstructorArgs([
        'person_title_override',
        [],
        $field_definition,
        [],
        'hidden',
        'default',
        [],
        $service,
        $messenger,
        $currentUser,
        $logger
      ])
      ->onlyMethods(['t'])
      ->getMock();
    $formatter->method('t')->willReturnArgument(0);
    return $formatter;
  }

  /**
   * Helper to create a field definition mock with a given name.
   *
   * @param string $field_name
   *   The field name to return from getName().
   *
   * @return \Drupal\Core\Field\FieldDefinitionInterface
   *   The field definition mock.
   */
  private function getFieldDefinition($field_name) {
    $field_definition = $this->createMock(FieldDefinitionInterface::class);
    $field_definition->method('getName')->willReturn($field_name);
    $field_definition->method('getType')->willReturn('string');
    return $field_definition;
  }
}
