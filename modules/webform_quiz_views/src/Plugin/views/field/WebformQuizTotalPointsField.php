<?php

namespace Drupal\webform_quiz_views\Plugin\views\field;

use Drupal\views\ResultRow;

/**
 * Webform quiz total points.
 *
 * @ViewsField("webform_quiz_total_points_field")
 */
class WebformQuizTotalPointsField extends WebformQuizScoreField {

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values) {
    /** @var \Drupal\webform\WebformSubmissionInterface $webform_submission */
    $webform_submission = $this->getEntity($values);

    if ($webform_submission && $webform_submission->access('view')) {
      $data = $webform_submission->getData();

      return $data['webform_quiz_total_points'];
    }

    return [];
  }

}
