<?php
$xpdo_meta_map['brOrderProduct']= array (
  'package' => 'brightfield',
  'version' => '1.1',
  'table' => 'br_order_products',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'product_id' => NULL,
    'kit_id' => NULL,
    'pack_id' => NULL,
    'order_id' => NULL,
    'count' => 1,
    'price' => 0,
    'cost' => 0,
    'discount' => 0,
    'archive' => NULL,
  ),
  'fieldMeta' => 
  array (
    'product_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => false,
    ),
    'kit_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => false,
    ),
    'pack_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => false,
    ),
    'order_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => false,
    ),
    'count' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => true,
      'default' => 1,
    ),
    'price' => 
    array (
      'dbtype' => 'decimal',
      'precision' => '12,2',
      'phptype' => 'float',
      'null' => true,
      'default' => 0,
    ),
    'cost' => 
    array (
      'dbtype' => 'decimal',
      'precision' => '12,2',
      'phptype' => 'float',
      'null' => true,
      'default' => 0,
    ),
    'discount' => 
    array (
      'dbtype' => 'decimal',
      'precision' => '12,2',
      'phptype' => 'float',
      'null' => true,
      'default' => 0,
    ),
    'archive' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'json',
      'null' => true,
    ),
  ),
  'indexes' => 
  array (
    'product_id' => 
    array (
      'alias' => 'product_id',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'product_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'order_id' => 
    array (
      'alias' => 'order_id',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'order_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'kit_id' => 
    array (
      'alias' => 'kit_id',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'kit_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'pack_id' => 
    array (
      'alias' => 'pack_id',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'pack_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
  'aggregates' => 
  array (
    'Product' => 
    array (
      'class' => 'msProduct',
      'local' => 'product_id',
      'foreign' => 'id',
      'owner' => 'foreign',
      'cardinality' => 'one',
    ),
    'OrderKit' => 
    array (
      'class' => 'brOrderKit',
      'local' => 'kit_id',
      'foreign' => 'kit_id',
      'owner' => 'foreign',
      'cardinality' => 'one',
    ),
    'Pack' => 
    array (
      'class' => 'brKit',
      'local' => 'pack_id',
      'foreign' => 'id',
      'owner' => 'foreign',
      'cardinality' => 'one',
    ),
    'Order' => 
    array (
      'class' => 'brOrder',
      'local' => 'order_id',
      'foreign' => 'id',
      'owner' => 'foreign',
      'cardinality' => 'one',
    ),
  ),
);
