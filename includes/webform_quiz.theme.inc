<?php

/**
 * @file
 * Contains theme-related hooks for the webform_quiz module.
 */

 use Drupal\webform\Utility\WebformElementHelper;

/**
 * Implements hook_theme().
 */
function webform_quiz_theme($existing, $type, $theme, $path) {
  $info = [
    'webform_quiz_answer_description' => [
      'variables' => [
        'sai_enable' => NULL,
        'sai_correct_answer_description' => NULL,
        'sai_incorrect_answer_description' => NULL,
        'is_user_correct' => NULL,
      ],
    ],
    'webform_quiz_quiz_result_summary' => [
      'variables' => ['webform_submission' => NULL, 'quiz_results' => TRUE],
    ],
    'webform_quiz_radios' => [
      'render element' => 'element',
    ],
  ];

  return $info;
}

/**
 * Implements template_preprocess_HOOK().
 */
function template_preprocess_webform_quiz_radios(&$variables) {
  template_preprocess_radios($variables);
}

/**
 * Prepares variables for webform_quiz_radios templates.
 *
 * @see \Drupal\webform\Plugin\WebformElement\OptionsBase
 */
function webform_quiz_preprocess_webform_quiz_radios(&$variables) {
  if (!WebformElementHelper::isWebformElement($variables['element'])) {
    return;
  }

  _webform_preprocess_options($variables);
}
