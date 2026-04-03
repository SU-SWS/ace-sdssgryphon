<?php

namespace Drupal\Tests\sdss_workgroup_tagging\Kernel\Form;

use Drupal\Core\Form\FormState;
use Drupal\taxonomy\Entity\Term;
use Drupal\KernelTests\KernelTestBase;
use Drupal\sdss_workgroup_tagging\Form\SdssWgTaggingForm;

/**
 * Kernel test for the SDSS Workgroup Tagging form.
 *
 * @group sdss_workgroup_tagging
 */
class SdssWgTaggingFormKernelTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'system',
    'user',
    'field',
    'taxonomy',
    'text',
    'sdss_workgroup_tagging',
    'test_sdss_workgroup_tagging',
  ];

  /**
   * Test the workgroup tagging form.
   */
  public function testWgTaggingForm() {

    // Get dependencies from the container.
    $config_factory = \Drupal::service('config.factory');
    $entity_type_manager = \Drupal::service('entity_type.manager');

    // Ensure config is installed.
    $this->installConfig(['test_sdss_workgroup_tagging']);

    // Install the taxonomy term entity schema.
    $this->installEntitySchema('taxonomy_term');

    // Clear vocabulary cache.
    \Drupal::entityTypeManager()->getStorage('taxonomy_vocabulary')->resetCache();

    $term = Term::create([
      'vid' => 'sdss_organization',
      'name' => 'Test Organization',
    ]);
    $term->save();
        $term = Term::create([
      'vid' => 'stanford_person_types',
      'name' => 'Test Person Type',
    ]);
    $term->save();

    // Instantiate the form.
    $form_object = new SdssWgTaggingForm($config_factory, $entity_type_manager);
    $form_state = new FormState();

    // Build the form.
    $form = $form_object->buildForm([], $form_state);

    // Assert the form has the expected fields.
    $this->assertArrayHasKey('enabled', $form);
    $this->assertArrayHasKey('tags_fieldsets', $form);

    // Simulate form submission.
    $form_state->setValue('enabled', TRUE);
    $form_state->setValue('workgroup-0', 'testgroup');
    $form_state->setValue('org-tag-term-0', []);
    $form_state->setValue('person-tag-term-0', []);
    $form_state->setValue('auto-removal-0', 1);
    $form_state->set('fieldset_count', 1);
    $form_state->set('removed_fields', []);

    // Submit the form.
    $form_object->submitForm($form, $form_state);

    // Check that the config was saved.
    $config = \Drupal::config('sdss_workgroup_tagging.settings');
    $this->assertTrue($config->get('enabled'));
    $tags = $config->get('tags');    
    $this->assertEquals('testgroup', $tags[0]['workgroup']);
  }

}