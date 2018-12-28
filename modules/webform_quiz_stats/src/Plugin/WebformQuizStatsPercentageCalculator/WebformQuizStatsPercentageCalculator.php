<?php

namespace Drupal\webform_quiz_stats\Plugin\WebformQuizStatsPercentageCalculator;

use Drupal\webform_quiz_stats\Plugin\WebformQuizStatsPercentageCalculatorBase;


/**
 * @WebformQuizStatsPercentageCalculator(
 *  id = "webform_quiz_stats_percentage_calculator",
 *  label = @Translation("The plugin ID."),
 * )
 */
class WebformQuizStatsPercentageCalculator extends WebformQuizStatsPercentageCalculatorBase {

  /**
   * {@inheritdoc}
   */
  public function calculate($args) {
    if (!isset($args['option'])) {

    }

    $option = $args['option'];

    /** @var \Drupal\webform\Entity\WebformSubmission[] $webform_submissions */
    $webform_submissions = $args['webform_submissions'];

    if (empty($webform_submissions)) {
      return 0;
    }

    $webform_submissions_with_this_option = [];
    $element_key = $args['element']['#webform_key'];

    foreach ($webform_submissions as $id => $webform_submission) {
      $submission_data = $webform_submission->getData();

      if ($submission_data[$element_key] === $option) {
        $webform_submissions_with_this_option[$id] = $webform_submission;
      }
    }

    return (count($webform_submissions_with_this_option) / count($webform_submissions)) * 100;
  }

}
