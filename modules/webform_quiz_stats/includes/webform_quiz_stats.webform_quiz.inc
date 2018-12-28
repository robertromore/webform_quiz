<?php

/**
 * @file
 * Contains webform_quiz_stats.webform_quiz.inc.
 */

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\Entity\WebformSubmission;
use Drupal\webform_quiz_stats\WebformQuizStats;

/**
 * Implements hook_webform_quiz_correct_answer_shown().
 *
 * @throws \Drupal\Component\Plugin\Exception\PluginException
 */
function webform_quiz_stats_webform_quiz_correct_answer_shown(AjaxResponse $ajax_response, &$element, FormStateInterface $form_state) {
  $counter = 0;

  $form_obj = $form_state->getFormObject();
  $webform = $form_obj->getWebform();

  // Load all the webform submissions so we can calculate the statistics.
  $query = Drupal::entityQuery('webform_submission')
    ->accessCheck(FALSE)
    ->condition('webform_id', $webform->id());
  $result = $query->execute();
  $webform_submissions = WebformSubmission::loadMultiple($result);

  $triggering_element = $form_state->getTriggeringElement();

  $percentage_calculator = WebformQuizStats::percentageCalculator();

  foreach ($element['#options'] as $machine_name => $option) {

    $content = [
      '#theme' => 'webform_quiz_stats_percentage',
      '#label_text' => $option,
      '#percentage' => WebformQuizStats::percentageCalculator()->calculate([
        'option' => $option,
        'webform_submissions' => $webform_submissions,
        'element' => $element,
      ]),
    ];

    $ajax_response->addCommand(new HtmlCommand(".js-webform-radios .form-item:eq($counter) label", $content));

    $counter++;
  }
}
