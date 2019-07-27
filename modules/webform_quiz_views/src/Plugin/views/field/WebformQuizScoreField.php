<?php

namespace Drupal\webform_quiz_views\Plugin\views\field;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\ResultRow;
use Drupal\webform_views\Plugin\views\field\WebformSubmissionFieldNumeric;
use ReflectionMethod;

/**
 * Webform quiz score.
 *
 * @ViewsField("webform_quiz_score_field")
 */
class WebformQuizScoreField extends WebformSubmissionFieldNumeric {

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    $reflectionMethod = new ReflectionMethod('\Drupal\views\Plugin\views\field\FieldPluginBase::buildOptionsForm');
    return $reflectionMethod->invokeArgs($this, [&$form, $form_state]);
  }

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values) {
    /** @var \Drupal\webform\WebformSubmissionInterface $webform_submission */
    $webform_submission = $this->getEntity($values);

    if ($webform_submission && $webform_submission->access('view')) {
      $data = $webform_submission->getData();

      return $data['webform_quiz_score'];
    }

    return [];
  }

  /**
   * {@inheritdoc}
   */
  protected function getWebformElementPlugin() {
    return NULL;
  }

}
