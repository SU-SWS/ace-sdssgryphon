<?php

namespace Drupal\sdss_entities\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the sdss entity entity edit forms.
 */
class SdssEntityForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $result = parent::save($form, $form_state);

    $entity = $this->getEntity();

    $message_arguments = ['%label' => $entity->toLink()->toString()];
    $logger_arguments = [
      '%label' => $entity->label(),
      'link' => $entity->toLink($this->t('View'))->toString(),
    ];

    switch ($result) {
      case SAVED_NEW:
        $this->messenger()->addStatus($this->t('New sdss entity %label has been created.', $message_arguments));
        $this->logger('sdss_entities')->notice('Created new sdss entity %label', $logger_arguments);
        break;

      case SAVED_UPDATED:
        $this->messenger()->addStatus($this->t('The sdss entity %label has been updated.', $message_arguments));
        $this->logger('sdss_entities')->notice('Updated sdss entity %label.', $logger_arguments);
        break;
    }

    $form_state->setRedirect('entity.sdss_entity.canonical', ['sdss_entity' => $entity->id()]);

    return $result;
  }

}
