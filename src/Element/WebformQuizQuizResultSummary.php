<?php

namespace Drupal\webform_quiz\Element;

use Drupal\Core\Render\Element\RenderElement;
use Drupal\webform_quiz\Model\WebformQuizResults;

/**
 * Provides a render element to display webform quiz results.
 *
 * @RenderElement("webform_quiz_quiz_result_summary")
 */
class WebformQuizQuizResultSummary extends RenderElement {

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    $class = get_class($this);

    return [
      '#theme' => 'webform_quiz_quiz_result_summary',
      '#webform_submission' => NULL,
      '#source_entity' => NULL,
      '#pre_render' => [
        [$class, 'preRenderWebformQuizQuizResultSummary'],
      ],
      '#theme_wrappers' => ['details'],
      '#summary_attributes' => [],
    ];
  }

  /**
   * Create webform submission information for rendering.
   *
   * @param array $element
   *   An associative array containing the properties and children of the
   *   element.
   *
   * @return array
   *   The modified element with webform submission information.
   */
  public static function preRenderWebformQuizQuizResultSummary(array $element) {
    /** @var \Drupal\webform\WebformSubmissionInterface $webform_submission */
    $webform_submission = $element['#webform_submission'];

    $data = $webform_submission->getData();

    // Add title.
    $element += [
      '#title' => t('Quiz Results'),
    ];

    $quiz_results =  WebformQuizResults::create($data);

    $element['#quiz_results'] = $quiz_results->toArray();

    return $element;
  }

}
