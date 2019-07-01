<?php

namespace Drupal\webform_quiz\Element;

use Drupal\Core\Render\Element\Radios;

/**
 * Provides a render element to display webform quiz radios.
 *
 * @RenderElement("webform_quiz_radios")
 */
class WebformQuizRadios extends Radios {

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    $info = parent::getInfo();
    $info['#theme_wrappers'] = ['webform_quiz_radios'];
    return $info;
  }

}
