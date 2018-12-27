<?php

namespace Drupal\webform_quiz\Form;

use Drupal;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\webform\Entity\Webform;
use Drupal\webform_quiz\Exception\WebformQuizIdMissingException;

/**
 * Form to add a webform entity that is a quiz.
 */
class WebformQuizAddForm extends FormBase {

  /**
   * @var \Drupal\Core\Entity\EntityInterface|\Drupal\webform\Entity\Webform $webform
   */
  protected $webform;

  /**
   * WebformQuizAddForm constructor.
   */
  public function __construct() {
    $this->webform = Webform::create([]);
  }


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'webform_quiz_add_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $webform = $this->webform;

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $webform->id(),
      '#machine_name' => [
        'exists' => '\Drupal\webform\Entity\Webform::load',
        'source' => ['title'],
        'label' => '<br/>' . $this->t('Machine name'),
      ],
      '#maxlength' => 32,
      '#field_suffix' => ' (' . $this->t('Maximum @max characters', ['@max' => 32]) . ')',
      '#disabled' => (bool) $webform->id(),
      '#required' => TRUE,
    ];
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Quiz Title'),
      '#id' => 'title',
      '#maxlength' => 255,
      '#size' => 64,
      '#weight' => '0',
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Next'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $webform = $this->webform;

    if (isset($values['id'])) {
      $webform->set('id', $values['id']);
      $webform->set('title', $values['title']);
      try {
        $quiz_settings = [
          'is_this_a_quiz' => '1',
        ];
        $webform->setThirdPartySetting('webform_quiz', 'settings', $quiz_settings);
        $webform->save();
        Drupal::messenger()->addMessage('Quiz added successfully.');
      } catch (EntityStorageException $e) {
        Drupal::messenger()->addError('Could not save the quiz.');
        Drupal::logger('webform_quiz')->error('Could not save the quiz: ' . $e->getMessage());
      }
    }
    else {
      throw new WebformQuizIdMissingException();
    }

    $form_state->setRedirectUrl(Url::fromRoute('entity.webform.edit_form', ['webform' => $this->webform->id()]));
  }

}
