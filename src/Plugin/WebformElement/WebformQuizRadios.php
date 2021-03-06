<?php

namespace Drupal\webform_quiz\Plugin\WebformElement;

use Drupal;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\Entity\Webform as WebformEntity;
use Drupal\webform\Plugin\WebformElement\Radios;
use Drupal\webform\WebformSubmissionInterface;
use Drupal\Core\Ajax\AfterCommand;

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

  const SAI_POSITION_AFTER_QUESTION = 0;

  const SAI_POSITION_AFTER_ALL_QUESTIONS = 1;

  /**
   * {@inheritdoc}
   */
  public function getDefaultProperties() {
    return [
      // Form display.
      'correct_answer' => [],
      'sai_enable' => FALSE,
      'sai_position' => self::SAI_POSITION_AFTER_ALL_QUESTIONS,
      'sai_allow_change' => FALSE,
      'sai_correct_answer_description' => NULL,
      'sai_incorrect_answer_description' => NULL,
    ] + parent::getDefaultProperties();
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

    $element_properties = $form_state->get('element_properties');

    $form['correct_answer_options'] = [
      '#type' => 'details',
      '#title' => $this->t('Correct Answer Options'),
      '#weight' => 100,
    ];

    $form['correct_answer_options']['sai_container'] = $this->getFormInlineContainer();

    $form['correct_answer_options']['sai_container']['sai_enable'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show answer immediately?'),
      '#description' => $this->t('If checked, the answer will be displayed immediately after the user enters an answer.'),
      '#default_value' => isset($element_properties['sai_enable']) ? $element_properties['sai_enable'] : FALSE,
    ];

    $form['correct_answer_options']['sai_container']['sai_position'] = [
      '#type' => 'radios',
      '#title' => $this->t('Answer Position'),
      '#description' => $this->t('Select where you want the answer information to be displayed.'),
      '#options' => [
        self::SAI_POSITION_AFTER_ALL_QUESTIONS => $this->t('After all questions.'),
        self::SAI_POSITION_AFTER_QUESTION => $this->t('Immediately after the question.'),
      ],
      '#states' => [
        'visible' => [
          ':input[name="sai_enable"]' => ['checked' => TRUE],
        ],
      ],
      '#default_value' => isset($element_properties['sai_position']) ? $element_properties['sai_position'] : self::SAI_POSITION_AFTER_ALL_QUESTIONS,
    ];

    $form['correct_answer_options']['sai_container']['sai_correct_answer_description'] = [
      '#type' => 'webform_html_editor',
      '#title' => $this->t('Correct Answer Description'),
      '#description' => $this->t('A description of why the answer is correct.'),
      '#default_value' => isset($element_properties['sai_correct_answer_description']) ? $element_properties['sai_correct_answer_description'] : '',
      '#states' => [
        'visible' => [
          ':input[name="sai_enable"]' => ['checked' => TRUE],
        ],
      ],
    ];

    $form['correct_answer_options']['sai_container']['sai_incorrect_answer_description'] = [
      '#type' => 'webform_html_editor',
      '#title' => $this->t('Incorrect Answer Description'),
      '#description' => $this->t('A description of why the answer is incorrect.'),
      '#default_value' => isset($element_properties['sai_incorrect_answer_description']) ? $element_properties['sai_incorrect_answer_description'] : '',
      '#states' => [
        'visible' => [
          ':input[name="sai_enable"]' => ['checked' => TRUE],
        ],
      ],
    ];

    $form['correct_answer_options']['sai_allow_change'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Allow user to change answer?'),
      '#description' => $this->t('If checked, the user will be able to update their answer after being shown the correct answer.'),
      '#default_value' => isset($element_properties['sai_allow_change']) ? $element_properties['sai_allow_change'] : FALSE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::validateConfigurationForm($form, $form_state);

    // Make sure no blank options get submitted. If they are, just remove them.
    $values = $form_state->getValues();
    foreach ($values['options'] as $value) {
      if (empty($value)) {
        unset($values['options'][$value]);
      }
    }
    $form_state->setValues($values);
  }

  /**
   * {@inheritdoc}
   */
  public function prepare(array &$element, WebformSubmissionInterface $webform_submission = NULL) {
    parent::prepare($element, $webform_submission);

    $answer_description_wrapper = [
      '#type' => 'container',
      '#attributes' => ['id' => 'answer-description-wrapper'],
    ];

    if (!isset($element['#sai_position']) || $element['#sai_position'] == self::SAI_POSITION_AFTER_ALL_QUESTIONS) {
      $element['#suffix'] = render($answer_description_wrapper);
    }

    $using_ajax = FALSE;
    list($form_id, ) = explode('--', $element['#webform_id']);
    $webform = WebformEntity::load($form_id);
    if ($webform && $webform->getSetting('ajax', TRUE)) {
      $using_ajax = TRUE;
    }

    $sai_enable = isset($element['#sai_enable']) && !empty($element['#sai_enable']);
    if ($sai_enable && $using_ajax) {
      $sai_allow_change = isset($element['#sai_allow_change']) && !empty($element['#sai_allow_change']);
      $data = $webform_submission->getElementData($element['#webform_key']);
      $default_value_set = isset($element['#default_value']) && !empty($element['#default_value']);
      $value_set = isset($element['#value']) && !empty($element['#value']);

      if ((!empty($data) || $default_value_set || $value_set) && !$sai_allow_change) {
        $answer_description = self::getAnswerDescriptionElement($element, [
          '#sai_enable' => $sai_enable,
          '#triggering_element' => $element,
        ]);
        $element['#attributes']['disabled'] = 'disabled';
        if (!isset($element['#sai_position']) || $element['#sai_position'] == self::SAI_POSITION_AFTER_ALL_QUESTIONS) {
          $element['#suffix'] = render($answer_description);
        }
      }

      $element['#ajax'] = [
        'callback' => [get_class($this), 'ajaxShowAnswerDescription'],
        'event' => 'change',
        'progress' => [
          'type' => 'throbber',
          'message' => NULL,
        ],
      ];
    }
  }

  /**
   * Ajax handler to help show the answer description when user clicks an
   * option.
   *
   * @param $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   */
  public static function ajaxShowAnswerDescription(&$form, FormStateInterface $form_state) {
    $ajax_response = new AjaxResponse();

    $triggering_element = $form_state->getTriggeringElement();
    $element_key = $triggering_element['#name'];

    /** @var \Drupal\webform\WebformSubmissionForm $form_obj */
    $form_obj = $form_state->getFormObject();
    $webform = $form_obj->getWebform();
    $element = $webform->getElement($element_key);
    $sai_enable = isset($element['#sai_enable']) && !empty($element['#sai_enable']);
    $sai_allow_change = !isset($element['#sai_allow_change']) || isset($element['#sai_allow_change']) && !empty($element['#sai_allow_change']);
    $build = self::getAnswerDescriptionElement($element, ['#sai_enable' => $sai_enable, '#triggering_element' => $triggering_element], FALSE);
    $webform_id = $element['#webform_id'];
    list($form_id, $input_name) = explode('--', $webform_id);
    $form_id = 'webform-submission-' . str_replace('_', '-', $form_id) . '-add-form';
    $parent_selector = sprintf('.%s .form-item-%s', $form_id, str_replace('_', '-', $input_name));
    $selector = sprintf('.%s input[name=%s]', $form_id, $input_name);

    if (!$sai_allow_change) {
      $ajax_response->addCommand(new InvokeCommand($selector, 'prop', ['disabled', 'true']));
    }

    foreach (['not-selected', 'disabled'] as $className) {
      $ajax_response->addCommand(new InvokeCommand($parent_selector, 'addClass', [$className]));
      $ajax_response->addCommand(new InvokeCommand($selector, 'addClass', [$className]));
    }

    $correct_answers = $element['#correct_answer'];
    $user_selected_value = $triggering_element['#default_value'];
    $is_user_correct = in_array($user_selected_value, $correct_answers);
    $class = 'incorrect';
    if ($is_user_correct) {
      $class = 'correct';
    }

    if (!isset($element['#sai_position']) || $element['#sai_position'] == self::SAI_POSITION_AFTER_ALL_QUESTIONS) {
      $ajax_response->addCommand(new HtmlCommand('#answer-description-wrapper', $build));
    }
    else {
      $ajax_response->addCommand(new AfterCommand($parent_selector . ":has([value=${user_selected_value}])", $build));
    }

    $ajax_response->addCommand(new InvokeCommand($parent_selector . ":has([value=${user_selected_value}])", 'removeClass', ['not-selected']));
    $ajax_response->addCommand(new InvokeCommand($parent_selector . ":has([value=${user_selected_value}])", 'addClass', [$class]));
    $ajax_response->addCommand(new InvokeCommand("${selector}[value=${user_selected_value}]", 'removeClass', ['not-selected']));
    $ajax_response->addCommand(new InvokeCommand("${selector}[value=${user_selected_value}]", 'addClass', [$class]));

    // Allow other modules to add ajax commands.
    Drupal::moduleHandler()->invokeAll('webform_quiz_answer_shown', [$ajax_response, $element, $form_state]);

    return $ajax_response;
  }

  public static function getAnswerDescriptionElement($element, array $props = [], $include_container = TRUE) {
    $correct_answer_description = isset($element['#sai_correct_answer_description']) ? $element['#sai_correct_answer_description'] : '';
    $incorrect_answer_description = isset($element['#sai_incorrect_answer_description']) ? $element['#sai_incorrect_answer_description'] : '';

    $build['#type'] = 'container';
    $build['#attributes']['id'] = 'answer-description-wrapper';
    $build['description'] = [
      '#type' => 'webform_quiz_answer_description',
      '#correct_answer' => $element['#correct_answer'],
      '#sai_correct_answer_description' => $correct_answer_description,
      '#sai_incorrect_answer_description' => $incorrect_answer_description,
    ] + $props;

    if ($include_container) {
      return $build;
    }
    else {
      return $build['description'];
    }
  }

}
