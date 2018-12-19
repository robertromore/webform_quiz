<?php

namespace Drupal\webform_quiz\Plugin\WebformElement;

use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\Plugin\WebformElement\Radios;
use Drupal\webform\WebformSubmissionInterface;

/**
 * Provides a 'webform_quiz_radios' element.
 *
 * @WebformElement(
 *   id = "webform_quiz_radios",
 *   api = "https://api.drupal.org/api/drupal/core!lib!Drupal!Core!Render!Element!Radios.php/class/Radios",
 *   label = @Translation("Webform Quiz Radios"),
 *   description = @Translation("Provides a form element for a set of radio buttons with a correct answer provided."),
 *   category = @Translation("Webform Quiz"),
 * )
 */
class WebformQuizRadios extends Radios {

  function getInfo() {
    $info = parent::getInfo();

    return $info;
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $form['options']['options']['#type'] = 'webform_quiz_webform_element_options';

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);

    // Modify the existing element description to distinguish it from the
    // correct answer description.
    $form['element_description']['description']['#title'] = $this->t('Element Description');

    // Add a WYSIWYG for the correct answer description.
    $form['element_description']['correct_answer_description'] = [
      '#type' => 'webform_html_editor',
      '#title' => $this->t('Correct Answer Description'),
      '#description' => $this->t('A description of why the correct answer is correct.'),
      '#weight' => 0,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function prepare(array &$element, WebformSubmissionInterface $webform_submission = NULL) {
    // This addresses an issue where the webform_quiz_radios element was not
    // appearing in the webform.
    $element['#type'] = 'radios';
    parent::prepare($element, $webform_submission);
  }

}
