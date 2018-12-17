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
    $webform = $webform_submission->getWebform();
    $elements = $webform->getElementsDecoded();

    $submission_data = $webform_submission->getData();

    $number_of_points_received = 0;
    $number_of_available_points = 0;

    foreach ($submission_data as $element_key => $element_value) {
      $element = $elements[$element_key];

      if (isset($element['#correct_answer'])) {
        $number_of_available_points++;

        if (is_string($element_value) && in_array($element_value, $element['#correct_answer'])) {
          // This indicates that the user answered the question correctly.
          $number_of_points_received++;
        }
      }
    }

    $this->results = WebformQuizResults::create([
      'webform_quiz_number_of_points_received' => $number_of_points_received,
      'webform_quiz_total_number_of_points' => $number_of_available_points,
      'webform_quiz_score' => ($number_of_points_received / $number_of_available_points) * 100
    ]);

  }

  /**
   * @return \Drupal\webform_quiz\Model\WebformQuizResults
   */
  public function getResults() {
    return $this->results;
  }

}
