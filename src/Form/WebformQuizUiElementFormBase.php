<?php

namespace Drupal\webform_quiz\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\SubformState;
use Drupal\webform_ui\Form\WebformUiElementFormBase;

/**
 * Provides a base class for webform element webforms.
 *
 * The basic workflow for handling webform elements.
 *
 * - Read the element.
 * - Build element's properties webform.
 * - Set the property values.
 * - Alter the element's properties webform.
 * - Process the element's properties webform.
 * - Validate the element's properties webform.
 * - Submit the element's properties webform.
 * - Get property values from the webform state's values.
 * - Remove default properties from the element's properties.
 * - Update element properties.
 */
class WebformQuizUiElementFormBase extends WebformUiElementFormBase {

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $parent_key = $form_state->getValue('parent_key');
    $key = $form_state->getValue('key');

    $form_obj = $form_state->getFormObject();

    if ($form_obj instanceof WebformUiElementFormBase) {
      $element_plugin = $form_obj->getWebformElementPlugin();
      $webform = $form_obj->getWebform();

      // Submit element configuration.
      // Generally, elements will not be processing any submitted properties.
      // It is possible that a custom element might need to call a third-party API
      // to 'register' the element.
      $subform_state = SubformState::createForSubform($form['properties'], $form, $form_state);
      $element_plugin->submitConfigurationForm($form, $subform_state);

      // Add/update the element to the webform.
      $properties = $element_plugin->getConfigurationFormProperties($form, $subform_state);

      // Store the correct answer based on what the user checked.
      // This will be an array.
      $user_input = $form_state->getUserInput();
      $items = $user_input['properties']['options']['custom']['options']['items'];
      $correct_answers = [];
      foreach ($items as $item) {
        if (!empty($item['is_correct_answer'])) {
          $correct_answers[$item['value']] = $item['value'];
        }
      }
      $properties['#correct_answer'] = $correct_answers;
      $webform->setElementProperties($key, $properties, $parent_key);

      // Save the webform.
      $webform->save();
    }

  }

}
