<?php

namespace Drupal\webform_quiz\EntitySettings;

use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\EntitySettings\WebformEntitySettingsBaseForm;

/**
 * Class WebformQuizWebformSettingsForm
 *
 * @package Drupal\webform_quiz\EntitySettings
 */
class WebformQuizWebformSettingsForm extends WebformEntitySettingsBaseForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    /** @var \Drupal\webform\WebformInterface $webform */
    $webform = $this->getEntity();
    $third_party_settings = $webform->getThirdPartySettings('webform_quiz');

    $form['is_this_a_quiz'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Is this a quiz?'),
      '#description' => $this->t('Check this box if this webform should be a quiz.'),
      '#default_value' => isset($third_party_settings['settings']['is_this_a_quiz']) ? $third_party_settings['settings']['is_this_a_quiz'] : FALSE,
    ];

    $form['quiz_settings'] = [
      '#type' => 'container',
      '#states' => [
        'visible' => [
          'input[name="is_this_a_quiz"]' => [
            'checked' => TRUE,
          ],
        ],
      ],
    ];

    $form['quiz_settings']['allow_retakes'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Allow retakes?'),
      '#default_value' => isset($third_party_settings['settings']['allow_retakes']) ? $third_party_settings['settings']['allow_retakes'] : TRUE,
    ];
    $form['quiz_settings']['retake_settings'] = [
      '#type' => 'container',
      '#states' => [
        'visible' => [
          'input[name="allow_retakes"]' => [
            'checked' => TRUE,
          ],
        ],
      ],
    ];
    $form['quiz_settings']['retake_settings']['number_of_retakes_allowed'] = [
      '#type' => 'number',
      '#title' => $this->t('Number of retakes allowed'),
      '#description' => $this->t('Enter the number of retakes a user can do. Enter 0 for an unlimited number of retakes.'),
      '#default_value' => isset($third_party_settings['settings']['number_of_retakes_allowed']) ? $third_party_settings['settings']['number_of_retakes_allowed'] : 0,
    ];
    $form['quiz_settings']['passing_score'] = [
      '#type' => 'number',
      '#title' => $this->t('Passing score percentage'),
      '#default_value' => isset($third_party_settings['settings']['passing_score']) ? $third_party_settings['settings']['passing_score'] : 70,
      '#min' => 0,
      '#max' => 100,
    ];

    $form += parent::form($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    // Save webform_quiz settings to the webform entity.

    $values = $form_state->getValues();

    $webform_quiz_settings['is_this_a_quiz'] = $values['is_this_a_quiz'];
    $webform_quiz_settings['allow_retakes'] = $values['allow_retakes'];
    $webform_quiz_settings['number_of_retakes_allowed'] = $values['number_of_retakes_allowed'];
    $webform_quiz_settings['passing_score'] = $values['passing_score'];

    /** @var \Drupal\webform\WebformInterface $webform */
    $webform = $this->getEntity();
    $webform->setThirdPartySetting('webform_quiz', 'settings', $webform_quiz_settings);

    // Set settings.
    $webform->setSettings($values);

    parent::save($form, $form_state);
  }

}
