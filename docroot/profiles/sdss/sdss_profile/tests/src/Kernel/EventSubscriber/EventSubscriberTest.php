<?php

namespace Drupal\Tests\sdss_profile\Kernel\EventSubscriber;

use Drupal\config_pages\ConfigPagesLoaderServiceInterface;
use Drupal\consumers\Entity\Consumer;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\core_event_dispatcher\Event\Entity\EntityInsertEvent;
use Drupal\default_content\Event\ImportEvent;
use Drupal\file\Entity\File;
use Drupal\KernelTests\KernelTestBase;
use Drupal\media\Entity\Media;
use Drupal\media\Entity\MediaType;
use Drupal\sdss_profile\EventSubscriber\EventSubscriber as StanfordEventSubscriber;
use Drupal\user\Entity\Role;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Class EventSubscriberTest.
 *
 * @group sdss_profile
 * @group event_subscriber
 * @coversDefaultClass \Drupal\sdss_profile\EventSubscriber\EventSubscriber
 */
class EventSubscriberTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'system',
    'node',
    'user',
    'consumers',
    'default_content',
    'field',
    'image',
    'file',
    'simple_oauth',
    'serialization',
    'media',
    'test_stanford_profile',
    'externalauth',
    'samlauth',
    'options',
  ];

  /**
   * Event subscriber object.
   *
   * @var \Drupal\sdss_profile\EventSubscriber\EventSubscriber
   */
  protected $eventSubscriber;

  /**
   * {@inheritDoc}
   */
  public function setup(): void {
    parent::setUp();
    $this->installEntitySchema('file');

    $this->installEntitySchema('node');
    $this->installEntitySchema('user');
    $this->installEntitySchema('consumer');
    $this->installEntitySchema('oauth2_token');
    $this->installEntitySchema('media');

    $file_system = \Drupal::service('file_system');
    $logger_factory = \Drupal::service('logger.factory');
    $messenger = \Drupal::messenger();
    $client = \Drupal::service('http_client');

    $this->eventSubscriber = new TestStanfordEventSubscriber($file_system, $client, $logger_factory, $messenger);

    /** @var \Drupal\media\MediaTypeInterface $media_type */
    $media_type = MediaType::create([
      'id' => 'image',
      'label' => 'image',
      'source' => 'image',
    ]);
    $media_type->save();

    // Create the source field.
    $source_field = $media_type->getSource()->createSourceField($media_type);
    $source_field->getFieldStorageDefinition()->save();
    $source_field->save();
    $media_type->set('source_configuration', [
      'source_field' => $source_field->getName(),
    ])->save();
  }

  /**
   * Test the consumer secret is randomized.
   */
  public function testConsumerSecretRandomized() {
    $this->assertContains('onContentImport', StanfordEventSubscriber::getSubscribedEvents());
    $consumer = Consumer::create([
      'client_id' => 'foobar',
      'label' => 'foobar',
      'secret' => 'foobar',
    ]);
    $consumer->save();
    $secret = $consumer->get('secret')->getString();
    $this->assertNotEquals('foobar', $secret);
    $event = new ImportEvent([$consumer], 'foobar');
    $this->eventSubscriber->onContentImport($event);
    $this->assertNotEquals($secret, $consumer->get('secret')->getString());
  }

  public function testContentImportEntity() {
    $file = File::create(['uri' => 'public://foobar.jpg']);
    $file->save();

    $this->assertFileDoesNotExist('public://foobar.jpg');

    /** @var \Drupal\media\MediaInterface $media */
    $media = Media::create([
      'bundle' => 'image',
      'field_media_image' => ['target_id' => $file->id()],
    ]);
    $event = new ImportEvent([$media], 'foobar');
    $this->eventSubscriber->onContentImport($event);

    $this->assertFileExists('public://foobar.jpg');
  }

  public function testUserInsert() {
    \Drupal::service('module_installer')->install(['samlauth']);
    $role = Role::create(['id' => 'test_role1', 'label' => 'Test role 1']);
    $role->save();

    $event = new EntityInsertEvent($role);
    $this->eventSubscriber->onEntityInsert($event);
    $saml_setting = \Drupal::config('samlauth.authentication')
      ->get('map_users_roles');

    $this->assertContains('test_role1', $saml_setting);
  }

}

/**
 * {@inheritDoc}
 */
class TestStanfordEventSubscriber extends StanfordEventSubscriber {

  /**
   * {@inheritDoc}
   */
  protected function downloadFile($source, $destination) {
    file_put_contents($destination, '');
    return $destination;
  }

}
