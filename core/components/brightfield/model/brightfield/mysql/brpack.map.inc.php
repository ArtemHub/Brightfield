<?php
$xpdo_meta_map['brPack']= array (
  'package' => 'brightfield',
  'version' => '1.1',
  'table' => 'br_packages',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'packid' => 0,
    'prodid' => 0,
    'menuindex' => 0,
  ),
  'fieldMeta' => 
  array (
    'packid' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'prodid' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'menuindex' => 
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
    'packid' => 
    array (
      'alias' => 'packid',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'packid' => 
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
    'Kit' => 
    array (
      'class' => 'brKit',
      'local' => 'packid',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
