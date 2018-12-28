<?php

/**
 * @file
 * Contains webform_quiz_stats.webform_quiz.inc.
 */

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\FormStateInterface;

/**
 * Implements hook_webform_quiz_correct_answer_shown().
 */
function webform_quiz_stats_webform_quiz_correct_answer_shown(AjaxResponse $ajax_response, &$element, FormStateInterface $form_state) {
  $counter = 0;
  foreach ($element['#options'] as $machine_name => $option) {
    $content = [
      '#theme' => 'webform_quiz_stats_percentage',
      '#label_text' => $option,
      '#percentage' => 100, // todo: calculate the percentage
    ];

    $ajax_response->addCommand(new HtmlCommand(".js-webform-radios .form-item:eq($counter) label", $content));

    $counter++;
  }
}
