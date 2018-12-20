<?php

namespace Drupal\webform_quiz\Element;

use Drupal\Core\Render\Element\RenderElement;

/**
 * Provides a render element to display webform descriptions.
 *
 * @RenderElement("webform_quiz_correct_answer_description")
 */
class WebformQuizCorrectAnswerDescription extends RenderElement {

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    return [
      '#theme' => 'webform_quiz_correct_answer_description',
      '#correct_answer_description' => NULL,
    ];
  }

}
