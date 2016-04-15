<?php
$xpdo_meta_map['brOrderKit']= array (
  'package' => 'brightfield',
  'version' => '1.1',
  'table' => 'br_order_kits',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'order_id' => 0,
    'kit_id' => 0,
  ),
  'fieldMeta' => 
  array (
    'order_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'kit_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
  ),
  'indexes' => 
  array (
    'kitid' => 
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
  ),
  'composites' => 
  array (
    'OrderProduct' => 
    array (
      'class' => 'brOrderProduct',
      'local' => 'kit_id',
      'foreign' => 'kit_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
  'aggregates' => 
  array (
    'User' => 
    array (
      'class' => 'modUser',
      'local' => 'user_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Kit' => 
    array (
      'class' => 'modResource',
      'local' => 'kit_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Order' => 
    array (
      'class' => 'brOrder',
      'local' => 'order_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
