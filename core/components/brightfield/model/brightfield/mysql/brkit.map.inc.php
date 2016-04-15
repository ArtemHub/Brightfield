<?php
$xpdo_meta_map['brKit']= array (
  'package' => 'brightfield',
  'version' => '1.1',
  'table' => 'br_kits',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'kitid' => 0,
    'title' => '',
    'menuindex' => 0,
  ),
  'fieldMeta' => 
  array (
    'kitid' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'title' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
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
    'kitid' => 
    array (
      'alias' => 'kitid',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'kitid' => 
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
    'Pack' => 
    array (
      'class' => 'brPack',
      'local' => 'id',
      'foreign' => 'packid',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
