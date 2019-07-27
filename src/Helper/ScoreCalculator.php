<?php

namespace Drupal\webform_quiz\Helper;


use Drupal\webform_quiz\Model\WebformQuizResults;
use Drupal\webform\Entity\WebformSubmission;

class ScoreCalculator {

  /**
   * @var \Drupal\webform\Entity\WebformSubmission
   */
  protected $webformSubmission;

  /**
   * @var \Drupal\webform_quiz\Model\WebformQuizResults
   */
  protected $results;

  /**
   * ScoreCalculator constructor.
   *
   * @param \Drupal\webform\Entity\WebformSubmission $webformSubmission
   */
  public function __construct(WebformSubmission $webformSubmission) {
    $this->webformSubmission = $webformSubmission;
    $this->calculate();
  }

  /**
   * Calculate the score based on the quiz submission.
   */
  protected function calculate() {
    $webform_submission = $this->webformSubmission;

    $points_received = 0;
    $available_points = 0;

    foreach ($webform_submission->getWebform()->getElementsInitializedAndFlattened() as $element) {
      if (isset($element['#correct_answer'])) {
        $available_points += $element['#webform_quiz_number_of_points'];
        $submission_data = $webform_submission->getElementData($element['#webform_key']);

        if (is_string($submission_data) && in_array($submission_data, $element['#correct_answer'])) {
          // This indicates that the user answered the question correctly.
          $points_received += $element['#webform_quiz_number_of_points'];
        }
      }
    }

    $this->results = WebformQuizResults::create([
      'webform_quiz_points_received' => $points_received,
      'webform_quiz_total_points' => $available_points,
      'webform_quiz_score' => ($points_received / $available_points) * 100
    ]);

  }

  /**
   * @return \Drupal\webform_quiz\Model\WebformQuizResults
   */
  public function getResults() {
    return $this->results;
  }

}
