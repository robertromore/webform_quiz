<?php

namespace Drupal\webform_quiz_views\Plugin\views\field;

use Drupal\views\ResultRow;

/**
 * Webform quiz points received.
 *
 * @ViewsField("webform_quiz_points_received")
 */
class WebformQuizPointsReceived extends WebformQuizScore {

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values) {
    /** @var \Drupal\webform\WebformSubmissionInterface $webform_submission */
    $webform_submission = $this->getEntity($values);

    if ($webform_submission && $webform_submission->access('view')) {
      $data = $webform_submission->getData();

      return $data['webform_quiz_number_of_points_received'];
    }

    return [];
  }

}
