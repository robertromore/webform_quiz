<?php

namespace Drupal\webform_quiz_stats\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a Webform quiz stats percentage calculator item annotation object.
 *
 * @see \Drupal\webform_quiz_stats\Plugin\WebformQuizStatsPercentageCalculatorManager
 * @see plugin_api
 *
 * @Annotation
 */
class WebformQuizStatsPercentageCalculator extends Plugin {


  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The label of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

}
