<?php

/**
 * @file
 * Contains theme-related hooks for the webform_quiz module.
 */

/**
 * Implements hook_theme().
 */
function webform_quiz_theme($existing, $type, $theme, $path) {
  $info = [
    'webform_quiz_correct_answer_description' => [
      'variables' => ['correct_answer_description' => NULL],
    ],
    'webform_quiz_quiz_result_summary' => [
      'variables' => ['webform_submission' => NULL, 'quiz_results' => TRUE],
    ],
  ];

  return $info;
}
