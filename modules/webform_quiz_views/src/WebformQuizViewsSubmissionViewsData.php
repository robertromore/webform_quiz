<?php

namespace Drupal\webform_quiz_views;

use Drupal\webform_views\WebformSubmissionViewsData;

/**
 * Views data for 'webform_submission' entity type.
 */
class WebformQuizViewsSubmissionViewsData extends WebformSubmissionViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $base_table = $this->entityType->getBaseTable() ?: $this->entityType->id();

    $data[$base_table]['webform_quiz_score'] = [
      'title' => $this->t("Webform Quiz: User's Score"),
      'help' => $this->t("The user's total score."),
      'field' => [
        'id' => 'webform_quiz_score',
      ],
    ];

    $data[$base_table]['webform_quiz_total_points'] = [
      'title' => $this->t("Webform Quiz: Total Number of Points"),
      'help' => $this->t("The total number of points for this quiz."),
      'field' => [
        'id' => 'webform_quiz_total_points',
      ],
    ];

    $data[$base_table]['webform_quiz_points_received'] = [
      'title' => $this->t("Webform Quiz: Number of Points Received"),
      'help' => $this->t("The total number of points the user received."),
      'field' => [
        'id' => 'webform_quiz_points_received',
      ],
    ];

    foreach ($this->webformStorage->loadMultiple() as $webform) {
      foreach ($webform->getElementsInitializedAndFlattened() as $element) {
        $data = array_replace_recursive($data, $this->getWebformElementViewsData($element, $webform));
      }
    }

    return $data;
  }

}
