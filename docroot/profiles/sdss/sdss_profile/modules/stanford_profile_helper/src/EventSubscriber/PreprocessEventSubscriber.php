<?php

namespace Drupal\stanford_profile_helper\EventSubscriber;

use Drupal\Core\Routing\TrustedRedirectResponse;
use Drupal\Core\Url;
use Drupal\node\NodeInterface;
use Drupal\preprocess_event_dispatcher\Event\NodePreprocessEvent;
use Drupal\rabbit_hole\BehaviorInvokerInterface;
use Drupal\rabbit_hole\Plugin\RabbitHoleBehaviorPluginInterface;
use Drupal\rabbit_hole\Plugin\RabbitHoleBehaviorPluginManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Subcribe to events for pre-processing.
 */
class PreprocessEventSubscriber implements EventSubscriberInterface {

  /**
   * Rabbit hole behavior invoker service.
   *
   * @var \Drupal\rabbit_hole\BehaviorInvokerInterface
   */
  protected $rabbitHoleBehavior;

  /**
   * Rabbit hole behavior plugin manager.
   *
   * @var \Drupal\rabbit_hole\Plugin\RabbitHoleBehaviorPluginManager
   */
  protected $rabbitHolePluginManager;

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    $events = [
      'preprocess_node' => 'preprocessNode',
    ];
    return $events;
  }

  /**
   * Event subscriber constructor.
   *
   * @param \Drupal\rabbit_hole\BehaviorInvokerInterface $rabbitHoleBehavior
   *   Rabbit hole behavior invoker service.
   * @param \Drupal\rabbit_hole\Plugin\RabbitHoleBehaviorPluginManager $rabbitHolePluginManager
   *   Rabbit hole behavior plugin manager.
   */
  public function __construct(BehaviorInvokerInterface $rabbitHoleBehavior, RabbitHoleBehaviorPluginManager $rabbitHolePluginManager) {
    $this->rabbitHoleBehavior = $rabbitHoleBehavior;
    $this->rabbitHolePluginManager = $rabbitHolePluginManager;
  }

  /**
   * When preprocessing the node page, add the rabbit hole behavior message.
   *
   * @param \Drupal\preprocess_event_dispatcher\Event\NodePreprocessEvent $event
   *   Triggered Event.
   */
  public function preprocessNode(NodePreprocessEvent $event) {
    $node = $event->getVariables()->get('node');
    if (
      $event->getVariables()->get('page') &&
      ($plugin = $this->getRabbitHolePlugin($node))
    ) {
      $redirect_response = $plugin->performAction($node);

      // The action returned from the redirect plugin might be to show the
      // page. If it is, we don't want to display the message.
      if ($redirect_response instanceof TrustedRedirectResponse) {
        $content = $event->getVariables()->getByReference('content');
        $message = [
          '#theme' => 'rabbit_hole_message',
          '#destination' => self::getTargetUrl($redirect_response),
        ];
        $event->getVariables()
          ->set('content', ['rh_message' => $message] + $content);
      }
    }
  }

  /**
   * Get the absolute target url from the rabbit hole settings.
   *
   * @param \Drupal\Core\Routing\TrustedRedirectResponse $redirect_response
   *   Rabbit hole redirect response.
   *
   * @return string
   *   Absolute url.
   */
  protected static function getTargetUrl(TrustedRedirectResponse $redirect_response): string {
    $target_url = $redirect_response->getTargetUrl();
    try {
      $url = Url::fromUserInput($target_url, ['absolute' => TRUE]);
    }
    catch (\Exception $e) {
      $url = Url::fromUri($target_url, ['absolute' => TRUE]);
    }
    return $url->toString(TRUE)->getGeneratedUrl();
  }

  /**
   * Get the rabbit hole behavior plugin for the given node.
   *
   * @param \Drupal\node\NodeInterface $entity
   *   Node with rabbit hole.
   *
   * @return \Drupal\rabbit_hole\Plugin\RabbitHoleBehaviorPluginInterface|null
   *   Redirect behavior plugin if applicable.
   */
  protected function getRabbitHolePlugin(NodeInterface $entity): ?RabbitHoleBehaviorPluginInterface {
    $values = $this->rabbitHoleBehavior->getRabbitHoleValuesForEntity($entity);
    if (isset($values['rh_action']) && $values['rh_action'] == 'page_redirect') {
      return $this->rabbitHolePluginManager->createInstance($values['rh_action'], $values);
    }
    return NULL;
  }

}
