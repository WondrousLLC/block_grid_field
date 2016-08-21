<?php

/**
 * @file
 * Contains \Drupal\block_grid_field\Plugin\Field\FieldWidget\BlockGridFieldWidget.
 */

namespace Drupal\block_grid_field\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'block_grid_widget' widget.
 *
 * @FieldWidget(
 *   id = "block_grid_widget",
 *   label = @Translation("Block Grid Field Widget"),
 *   field_types = {
 *     "block_grid_type"
 *   }
 * )
 */
class BlockGridFieldWidget extends WidgetBase {
  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $configService = \Drupal::service('config.storage');
    $item_width_config = $configService->read('block_grid_field.settings')['item_width'];
    $item_width_select_options = [];
    $column_count_config = $configService->read('block_grid_field.settings')['column_count'];
    $column_count_select_options = [];

    foreach ($item_width_config as $config) {
      if (!$config) {
        continue;
      }

      $item_width_select_options[$config['key']] = $config['display_name'];
    }

    foreach ($column_count_config as $config) {
      if (!$config) {
        continue;
      }

      $column_count_select_options[$config['key']] = $config['display_name'];
    }

    $element['column_count_value'] = [
      '#type' => 'select',
      '#default_value' => isset($items[$delta]->column_count_value) ? $items[$delta]->column_count_value : NULL,
      '#options' => $column_count_select_options,
      '#theme' => 'block_grid_select',
      '#suffix' => '<div class="form-item suffix label">' . t('Columns'). '</div>'
    ];

    $element['item_width_value'] = [
      '#type' => 'select',
      '#default_value' => isset($items[$delta]->item_width_value) ? $items[$delta]->item_width_value : NULL,
      '#options' => $item_width_select_options,
      '#theme' => 'block_grid_select',
      '#suffix' => '<div class="form-item suffix label">' . t('Width'). '</div>'
    ];

    $item_width_preselect = isset($items[$delta]->item_width_value) ? $items[$delta]->item_width_value : array_keys($item_width_select_options)[0];
    $column_count_preselect = isset($items[$delta]->column_count_value) ? $items[$delta]->column_count_value : array_keys($item_width_select_options)[0];

    // If cardinality is 1, ensure a label is output for the field by wrapping
    // it in a details element.
    if ($this->fieldDefinition->getFieldStorageDefinition()->getCardinality() == 1) {
      $element += array(
        '#type' => 'fieldset',
        '#attributes' => array('class' => array('block-grid-field-wrapper')),
        '#field_suffix' => $this->renderIndicator($item_width_preselect, $column_count_preselect)
      );
    }

    return $element;
  }

  private function renderIndicator($item_width, $column_count) {
    $html = '<div class="block-grid--indicator">';
    $html .= "<ul class=\"{$item_width}\">";

    for ($i = 1; $i <= $column_count; $i++) {
      $html .= '<li><div>&nbsp;</div></li>';
    }

    $html .= '</ul>';
    $html .= '</div>';

    return $html;
  }
}
