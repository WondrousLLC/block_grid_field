<?php

/**
 * @file
 * Contains \Drupal\block_grid_field\Plugin\Field\FieldFormatter\BlockGridFieldFormatter.
 */

namespace Drupal\block_grid_field\Plugin\Field\FieldFormatter;

use Drupal\block_grid_field\Plugin\Field\FieldType\BlockGridFieldType;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'block_grid_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "block_grid_formatter",
 *   label = @Translation("Block Grid formatter"),
 *   field_types = {
 *     "block_grid_type"
 *   }
 * )
 */
class BlockGridFieldFormatter extends FormatterBase {
  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return array(// Implement default settings.
    ) + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    return array(// Implement settings form.
    ) + parent::settingsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    // Implement settings summary.

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];

    foreach ($items as $delta => $item) {
      /** @var BlockGridFieldType $item */
      // Render each element as markup.
      $element[$delta] = [
        '#type' => 'markup',
        '#markup' => $item->getValue(),
      ];
    }

    return $element;
  }
}
