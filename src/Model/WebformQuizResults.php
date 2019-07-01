<?php

namespace Drupal\webform_quiz\Model;

/**
 * Represents a data model for a webform submission's quiz results.
 */
class WebformQuizResults {

  /**
   * @var integer
   */
  protected $pointsReceived;

  /**
   * @var integer
   */
  protected $totalPoints;

  /**
   * @var double
   */
  protected $score;

  /**
   * QuizResults constructor.
   *
   * @param int $pointsReceived
   * @param int $totalPoints
   * @param float $score
   */
  public function __construct($pointsReceived, $totalPoints, $score) {
    $this->numberOfPointsReceived = $pointsReceived;
    $this->totalNumberOfPoints = $totalPoints;
    $this->score = $score;
  }

  public static function create($data) {
    return new static(
      $data['webform_quiz_number_of_points_received'],
      $data['webform_quiz_total_number_of_points'],
      $data['webform_quiz_score']
    );
  }

  /**
   * @return int
   */
  public function getNumberOfPointsReceived() {
    return $this->numberOfPointsReceived;
  }

  /**
   * @return int
   */
  public function getTotalNumberOfPoints() {
    return $this->totalNumberOfPoints;
  }

  /**
   * @return float
   */
  public function getScore() {
    return $this->score;
  }

  public function toArray() {
    return [
      'webform_quiz_number_of_points_received' => $this->getNumberOfPointsReceived(),
      'webform_quiz_total_number_of_points' => $this->getTotalNumberOfPoints(),
      'webform_quiz_score' => $this->getScore()
    ];
  }

}
