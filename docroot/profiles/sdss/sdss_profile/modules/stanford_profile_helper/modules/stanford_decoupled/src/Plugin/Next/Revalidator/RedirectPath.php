<?php

namespace Drupal\stanford_decoupled\Plugin\Next\Revalidator;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Utility\Error;
use Drupal\next\Event\EntityActionEvent;
use Drupal\next\Plugin\ConfigurableRevalidatorBase;
use Drupal\next\Plugin\RevalidatorInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Provides a revalidator for paths.
 *
 * @Revalidator(
 *  id = "redirect_path",
 *  label = "Redirect Path",
 *  description = "Path-based on-demand revalidation."
 * )
 */
class RedirectPath extends ConfigurableRevalidatorBase implements RevalidatorInterface {

  /**
   * {@inheritdoc}
   *
   * @codeCoverageIgnore Nothing to gets
   */
  public function defaultConfiguration() {
    return [];
  }

  /**
   * {@inheritdoc}
   *
   * @codeCoverageIgnore Nothing to gets
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {}

  /**
   * {@inheritdoc}
   */
  public function revalidate(EntityActionEvent $event): bool {
    $revalidated = FALSE;
    $sites = $event->getSites();
    if (!count($sites)) {
      return FALSE;
    }
    $entity = $event->getEntity();
    if ($entity->getEntityTypeId() != 'redirect') {
      return FALSE;
    }
    $paths = ['/' . $entity->getSource()['path']];

    foreach ($paths as $path) {
      foreach ($sites as $site) {
        try {
          if (method_exists($site, 'getRevalidateUrlForPath')) {
            $revalidate_url = $site->getRevalidateUrlForPath($path);
          }
          else {
            $revalidate_url = $site->buildRevalidateUrl(['path' => $path]);
          }

          if (!$revalidate_url) {
            throw new \Exception('No revalidate url set.');
          }

          if ($this->nextSettingsManager->isDebug()) {
            $this->logger->notice('(@action): Revalidating path %path for the site %site. URL: %url', [
              '@action' => $event->getAction(),
              '%path' => $path,
              '%site' => $site->label(),
              '%url' => $revalidate_url->toString(),
            ]);
          }

          $response = $this->httpClient->request('GET', $revalidate_url->toString());
          if ($response && $response->getStatusCode() === Response::HTTP_OK) {
            if ($this->nextSettingsManager->isDebug()) {
              $this->logger->notice('(@action): Successfully revalidated path %path for the site %site. URL: %url', [
                '@action' => $event->getAction(),
                '%path' => $path,
                '%site' => $site->label(),
                '%url' => $revalidate_url->toString(),
              ]);
            }

            $revalidated = TRUE;
          }
        }
        catch (\Exception $exception) {
          Error::logException($this->logger, $exception);
          $revalidated = FALSE;
        }
      }
    }

    return $revalidated;
  }

}
