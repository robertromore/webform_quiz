<?php

namespace Drupal\webform_quiz\Plugin\WebformQuizSubmitHandler;

use Drupal\Core\Plugin\PluginBase;
use Drupal\webform_quiz\Plugin\WebformQuizSubmitHandlerInterface;


/**
 * @WebformQuizSubmitHandler(
 *  id = "webform_quiz_element_config_submit",
 *  label = @Translation("Webform Quiz Element Config Submit."),
 * )
 */
class WebformQuizElementConfigSubmit extends PluginBase implements WebformQuizSubmitHandlerInterface {


    /**
    * {@inheritdoc}
    */
    public function build() {
      $build = [];

      // Implement your logic

      return $build;
    }
  
}
