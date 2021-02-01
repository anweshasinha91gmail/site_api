<?php

/**
 * Contains \Drupal\site_api\Controller\DetailsController.
 */
namespace Drupal\site_api\Controller;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class DetailsController extends FormBase {

    public  function getFormId() {
      // TODO: Implement getFormId() method.
      return 'node_details_form';
    }
    public function buildForm(array $form, FormStateInterface $form_state) {
        $form['site_api_key'] = array(
            '#type' => 'textfield',
            '#title' => t('Site API Key'),
            '#description' => t('Please add your site api key in site information form in /admin/config/system/site-information, if it doesnot exist.'),
            '#required' => TRUE,
        );
        $form['node_id'] = array(
            '#type' => 'textfield',
            '#title' => t('Nid'),
            '#description' => t('Nid of the node whose details you want to fetch'),
            '#required' => TRUE,
        );
        $form['actions']['#type'] = 'actions';
        $form['actions']['submit'] = array(
              '#type' => 'submit',
              '#value' => $this->t('Submit'),
              '#button_type' => 'primary',
        );
        return $form;

    }
    public function validateForm(array &$form, FormStateInterface $form_state) {
        $api_key = $form_state->getValue('site_api_key');
        $config_var = \Drupal::service('config.factory')->getEditable('mymodule.settings');
        $site_api_key = $config_var->get('siteapikey');
        if($api_key != $site_api_key) {
            $form_state->setErrorByName('site_api_key', t('API key did not match! Access Denied!'));
        }
    }
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $api_key = $form_state->getValue('site_api_key');
        $node_nid = $form_state->getValue('node_id');
        $serializer = \Drupal::service('serializer');
        $node_details = \Drupal\node\Entity\Node::load($node_nid);
        $node_type = $node_details->getType();
        if($node_type == 'page') {
            $data = $serializer->serialize($node_details, 'json', ['plugin_id' => 'entity']);
            drupal_set_message($data);
        } else {
            drupal_set_message('Only the details of a node of type Basic Page can be displayed', 'error');
        }
    }
  
}
  
  
