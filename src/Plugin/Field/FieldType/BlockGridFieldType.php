<?php

/**
 * @file
 * Contains \Drupal\block_grid_field\Plugin\Field\FieldType\BlockGridFieldType.
 */

namespace Drupal\block_grid_field\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'block_grid_type' field type.
 *
 * @FieldType(
 *   id = "block_grid_type",
 *   label = @Translation("Block Grid"),
 *   description = @Translation("Select a the properties for the grid"),
 *   default_widget = "block_grid_widget",
 *   default_formatter = "block_grid_formatter"
 * )
 */
class BlockGridFieldType extends FieldItemBase {
  /**
   * {@inheritdoc}
   */
  public static function defaultStorageSettings() {
    return parent::defaultStorageSettings();
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['column_count_value'] = DataDefinition::create('string')
      ->setLabel(t('Column Count'))
      ->setRequired(TRUE);
    $properties['item_width_value'] = DataDefinition::create('string')
      ->setLabel(t('Item Width'))
      ->setRequired(TRUE);

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    $schema = array(
      'columns' => array(
        'column_count_value' => array(
          'type' => 'varchar',
          'length' => 34,
          'not null' => TRUE,
        ),
        'item_width_value' => array(
          'type' => 'varchar',
          'length' => 34,
          'not null' => TRUE,
        ),
      ),
    );

    return $schema;
  }

  /**
   * {@inheritdoc}
   */
  public function getConstraints() {
    return parent::getConstraints();
  }

  /**
   * The fully prefixed class name of the column count
   *
   * @return string
   */
  public function getColumnWidthClass() {
    return $this->getSingleClassString($this->get('column_count_value')->getValue(), 'column_count_prefix');
  }

  /**
   * The fully prefixed class name of the item width
   *
   * @return string
   */
  public function getItemCountClass() {
    return $this->getSingleClassString($this->get('item_width_value')->getValue(), 'item_width_prefix');
  }

  /**
   * @param string $value
   * @param string $prefix_config_key
   * @return string
   */
  private function getSingleClassString($value, $prefix_config_key) {
    $config = \Drupal::config('block_grid_field.settings');
    $strings = [
      $config->get('class_prefix'),
      $config->get($prefix_config_key),
      $value,
    ];

    array_filter($strings);

    return implode('-', $strings);
  }

  /**
   * {@inheritdoc}
   */
  public function getString() {
    return $this->getColumnWidthClass() . ' ' . $this->getItemCountClass();
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $items = $this->get('item_width_value')->getValue();

    return $items === NULL;
  }
}
