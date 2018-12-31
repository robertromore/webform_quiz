<?php

namespace Drupal\webform_quiz_stats;

use Drupal;

abstract class WebformQuizStats {

  /**
   * Load the calculator to calculate a percentage.
   *
   * @return \Drupal\webform_quiz_stats\Plugin\WebformQuizStatsPercentageCalculator\WebformQuizStatsPercentageCalculator
   * @throws \Drupal\Component\Plugin\Exception\PluginException
   */
  public static function percentageCalculator() {
    /** @var \Drupal\Component\Plugin\PluginManagerInterface $plugin_manager */
    $plugin_manager = Drupal::service('plugin.manager.webform_quiz_stats_percentage_calculator');

    /** @var \Drupal\webform_quiz_stats\Plugin\WebformQuizStatsPercentageCalculator\WebformQuizStatsPercentageCalculator $instance */
    $instance = $plugin_manager->createInstance('webform_quiz_stats_percentage_calculator');

    return $instance;
  }

}
