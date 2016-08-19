<?php

/**
 * @file
 * Contains \Drupal\block_grid_field\Form\BlockGridFieldForm.
 */

namespace Drupal\block_grid_field\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\Color as ColorUtility;

/**
 * Class BlockGridFieldForm.
 *
 * @package Drupal\block_grid_field\Form
 */
class BlockGridFieldForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'block_grids_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'block_grid_field.settings_item_width',
      'block_grid_field.settings_item_width_prefix',
      'block_grid_field.settings_column_count',
      'block_grid_field.settings_column_count_prefix',
      'block_grid_field.settings_class_prefix',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('block_grid_field.settings');
    $item_width_setting_string = '';

    foreach ($config->get('item_width') as $item_width) {
      $item_width_setting_string .= isset($item_width['key']) ? $item_width['key'] . '|' . $item_width['display_name'] : '';
      $item_width_setting_string .= "\r\n";
    }

    $column_count_setting_string = '';

    foreach ($config->get('column_count') as $column_count) {
      $column_count_setting_string .= isset($column_count['key']) ? $column_count['key'] . '|' . $column_count['display_name'] : '';
      $column_count_setting_string .= "\r\n";
    }

    $form['description'] = [
      '#markup' => '<p>' . t('Configure how to output the block grid classes.') . '</p>',
    ];

    $form['prefix'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Class Prefix'),
    ];

    $form['prefix']['custom_class_prefix'] = [
      '#type' => 'textfield',
      '#size' => 34,
      '#maxlength' => 34,
      '#default_value' => $config->get('class_prefix'),
      '#description' => t("The prefix for every rendered class."),
    ];

    $form['items'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Items Width'),
    ];

    $form['items']['custom_item_widths'] = [
      '#type' => 'textarea',
      '#cols' => 60,
      '#rows' => 5,
      '#resizable' => 'vertical',
      '#required' => true,
      '#default_value' => $item_width_setting_string,
      '#description' => t("A list of <code>integer|Label</code> that will be provided in the \"Block Grid\" dropdown. Example: <code>1|One</code>.<br>These values will be available as classes."),
    ];

    $form['items']['custom_item_width_prefix'] = [
      '#type' => 'textfield',
      '#title' => t('Prefix'),
      '#size' => 34,
      '#maxlength' => 34,
      '#default_value' => $config->get('item_width_prefix'),
      '#description' => t("The prefix for the item width."),
    ];

    $form['columns'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Columns Count'),
    ];

    $form['columns']['custom_column_counts'] = [
      '#type' => 'textarea',
      '#cols' => 60,
      '#rows' => 5,
      '#required' => true,
      '#resizable' => 'vertical',
      '#default_value' => $column_count_setting_string,
      '#description' => t("A list of <code>integer|Label</code> that will be provided in the \"Block Grid\" dropdown. Example: <code>1|One</code>.<br>These values will be available as classes."),
    ];

    $form['columns']['custom_column_count_prefix'] = [
      '#type' => 'textfield',
      '#title' => t('Prefix'),
      '#size' => 34,
      '#maxlength' => 34,
      '#default_value' => $config->get('column_count_prefix'),
      '#description' => t("The prefix for the column count."),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $custom_config = $this->getKeyLabelList($form_state->getValue('custom_item_widths'));

    foreach($custom_config as $config) {
      if (empty($config['key']) || empty($config['display_name'])) {
        $form_state->setErrorByName('', t('<code>key</code> and <code>display_name</code> can not be empty.'));
      }
    }

    $custom_config = $this->getKeyLabelList($form_state->getValue('custom_column_counts'));

    foreach($custom_config as $config) {
      if (empty($config['key']) || empty($config['display_name'])) {
        $form_state->setErrorByName('', t('<code>key</code> and <code>display_name</code> can not be empty.'));
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $item_widths_config = $this->getKeyLabelList($form_state->getValue('custom_item_widths'));
    $column_counts_config = $this->getKeyLabelList($form_state->getValue('custom_column_counts'));

    \Drupal::configFactory()->getEditable('block_grid_field.settings')
      ->set('item_width_prefix', $form_state->getValue('custom_item_width_prefix'))
      ->set('item_width', $item_widths_config)
      ->set('column_count_prefix', $form_state->getValue('custom_column_count_prefix'))
      ->set('column_count', $column_counts_config)
      ->set('class_prefix', $form_state->getValue('custom_class_prefix'))
      ->save();
  }

  private function getKeyLabelList($custom_string) {
    $string_lines = array_filter(explode("\n", str_replace("\r\n", "\n", $custom_string)), 'trim');
    $config = [];

    foreach ($string_lines as $index => $line) {
      $line_settings = explode('|', $line, 3);

      if (isset($line_settings[0])) {
        $config[$index]['key'] = $line_settings[0];
      }

      if (isset($line_settings[1])) {
        $config[$index]['display_name'] = $line_settings[1];
      }
    }

    return $config;
  }
}
