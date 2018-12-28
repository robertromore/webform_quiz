<?php

namespace Drupal\webform_quiz_stats\Plugin;

use Drupal\Component\Plugin\PluginInspectionInterface;

/**
 * Defines an interface for Webform quiz stats percentage calculator plugins.
 */
interface WebformQuizStatsPercentageCalculatorInterface extends PluginInspectionInterface {

  /**
   * Returns a percentage between 0 and 100 for users for each webform radios
   * option for the users that selected that option.
   *
   * @param array $args
   *
   * @return mixed
   */
  public function calculate($args);

}
