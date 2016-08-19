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
      ->setLabel(t('Text value'))
      ->setRequired(TRUE);
    $properties['item_width_value'] = DataDefinition::create('string')
      ->setLabel(t('Text value'))
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
   * {@inheritdoc}
   */
  public function isEmpty() {
    $items = $this->get('items')->getValue();
    $width = $this->get('width')->getValue();

    return $items === NULL || $items === '' || $width === NULL || $width === '';
  }
}
