<?php

namespace Drupal\sdss_profile\Plugin\InstallTask;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Password\PasswordGeneratorInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\State\StateInterface;
use Drupal\externalauth\AuthmapInterface;
use Drupal\sdss_profile\InstallTaskBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * SNOW site settings installation.
 *
 * @InstallTask(
 *   id="sdss_profile_site_settings"
 * )
 */
class SiteSettings extends InstallTaskBase implements ContainerFactoryPluginInterface {

  /**
   * Authmap service.
   *
   * @var \Drupal\externalauth\AuthmapInterface
   */
  protected $authmap;

  /**
   * Password generator service.
   *
   * @var \Drupal\Core\Password\PasswordGeneratorInterface
   */
  protected $passwordGenerator;

  /**
   * State Service.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('externalauth.authmap'),
      $container->get('password_generator'),
      $container->get('state')
    );
  }

  /**
   * {@inheritDoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entityTypeManager, AuthmapInterface $authmap, PasswordGeneratorInterface $password_generator, StateInterface $state) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entityTypeManager;
    $this->authmap = $authmap;
    $this->passwordGenerator = $password_generator;
    $this->state = $state;
  }

  /**
   * {@inheritDoc}
   */
  public function runTask(array &$install_state) {
    $this->state->set('nobots', TRUE);

    $node_pages = [
      '403_page' => '4b8018dc-49a6-4018-9c54-e8c3e462beee',
      '404_page' => '6d51339d-ff67-498d-98e9-d8228d36fd51',
      'front_page' => '72f0069b-f1ec-4122-af73-6aa841faea90',
    ];

    // @codeCoverageIgnoreStart
    foreach ($node_pages as $page => $uuid) {
      if ($node = $this->getNode($uuid)) {
        $this->state->set("sdss_profile.$page", '/node/' . $node->id());
      }
    }
    // @codeCoverageIgnoreEnd
  }

  /**
   * Add a user with the site manager role.
   *
   * @param string $sunet
   *   User SunetId.
   * @param string $email
   *   User email.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  protected function addSiteOwner($sunet, $email) {
    $new_user = $this->entityTypeManager->getStorage('user')->create([
      'name' => $sunet,
      'pass' => $this->passwordGenerator->generate(),
      'mail' => $email,
      'roles' => ['site_manager'],
      'status' => 1,
    ]);
    $new_user->save();
    $this->authmap->save($new_user, 'simplesamlphp_auth', $sunet);
  }

  /**
   * Load a node by the UUID value.
   *
   * @param string $uuid
   *   Node uuid.
   *
   * @return \Drupal\node\NodeInterface
   *   Node object.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  protected function getNode($uuid) {
    $nodes = $this->entityTypeManager->getStorage('node')
      ->loadByProperties(['uuid' => $uuid]);
    return reset($nodes);
  }

}
