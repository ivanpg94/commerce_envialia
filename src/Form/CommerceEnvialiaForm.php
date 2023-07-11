<?php

namespace Drupal\commerce_envialia\Form;


use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;

class CommerceEnvialiaForm extends FormBase
{
  public function getFormId()
  {
    return 'commerce_envialia_form';
  }
  protected function getEditableConfigNames()
  {
    return [
      'commerce_envialia.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('commerce_envialia.settings');
//Title
    $form['overview'] = [
      '#markup' => t('Envialia API Credentials'),
      '#prefix' => '<p><strong>',
      '#suffix' => '</strong></p>',
    ];
//Envialia API URL contenedor

    $form['api_url_settings'] = [
      '#title' => t('Envialia API URL'),
      '#description' => t(''),
      '#type' => 'fieldset',
      '#collapsable' => TRUE,
      '#collapsed' => FALSE,
    ];
//Envialia API URL
    $form['api_url_settings']['api_url'] = [
      '#title' => t('Envialia API URL'),
      '#description' => t('Url to conect with envialia webservice.'),
      '#type' => 'textfield',
      '#size' => '30',
      '#placeholder' => 'http://example.com',
      '#required' => FALSE,
      '#default_value' => \Drupal::config('commerce_envialia.settings')->get('commerce_envialia_api_url'),
    ];
//Envialia API Agency
    $form['api_url_settings']['api_agency'] = [
      '#title' => t('Envialia API Agency'),
      '#description' => t('Agency to login to the envialia webservice.'),
      '#type' => 'textfield',
      '#size' => '30',
      '#placeholder' => '00000',
      '#required' => FALSE,
      '#default_value' => \Drupal::config('commerce_envialia.settings')->get('commerce_envialia_api_agency'),
    ];
//Envialia API User
    $form['api_url_settings']['api_user'] = [
      '#title' => t('Envialia API User'),
      '#description' => t('User to login to the envialia webservice.'),
      '#type' => 'textfield',
      '#size' => '30',
      '#placeholder' => '0000',
      '#required' => FALSE,
      '#default_value' => \Drupal::config('commerce_envialia.settings')->get('commerce_envialia_api_user'),
    ];
//Envialia API Password
    $form['api_url_settings']['api_password'] = [
      '#title' => t('Envialia API Password'),
      '#description' => t('Password to login to the envialia webservice.'),
      '#type' => 'textfield',
      '#size' => '30',
      '#placeholder' => '0000',
      '#required' => FALSE,
      '#default_value' => \Drupal::config('commerce_envialia.settings')->get('commerce_envialia_api_password'),
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => t('Save Settings'),
    ];
    return $form;

  }

  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    $api_url = $form_state->getValue('api_url');


    if (empty($api_url)) {
      $form_state->setErrorByName('api_url', $this->t('this field is required '));
    }
    if (empty($api_url)) {
      $form_state->setErrorByName('api_agency', $this->t('this field is required '));
    }
    if (empty($api_url)) {
      $form_state->setErrorByName('api_user', $this->t('this field is required '));
    }
    if (empty($api_url)) {
      $form_state->setErrorByName('api_password', $this->t('this field is required '));
    }
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $form_state->setRebuild(TRUE);

    // Save Commerce Google Trusted Store Variables.
    \Drupal::configFactory()->getEditable('commerce_envialia.settings')->set('commerce_envialia_api_url', $form_state->getValue(['api_url']))->save();
    \Drupal::configFactory()->getEditable('commerce_envialia.settings')->set('commerce_envialia_api_agency', $form_state->getValue(['api_agency']))->save();
    \Drupal::configFactory()->getEditable('commerce_envialia.settings')->set('commerce_envialia_api_user', $form_state->getValue(['api_user']))->save();
    \Drupal::configFactory()->getEditable('commerce_envialia.settings')->set('commerce_envialia_api_password', $form_state->getValue(['api_password']))->save();

  }

}
