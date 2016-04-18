<?php
$xpdo_meta_map['brOrder']= array (
  'package' => 'brightfield',
  'version' => '1.1',
  'table' => 'br_orders',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'manager_id' => 0,
    'client_id' => 0,
    'client_name' => '',
    'createdon' => '0000-00-00 00:00:00',
    'updatedon' => '0000-00-00 00:00:00',
    'num' => '',
    'cost' => 0,
    'price' => 0,
    'discount' => 0,
    'status' => 0,
    'city' => '',
    'address' => '',
    'phone' => '',
    'company' => '',
    'context' => 'web',
    'comment' => NULL,
    'total' => 0,
    'properties' => NULL,
  ),
  'fieldMeta' => 
  array (
    'manager_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'client_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'client_name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'createdon' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => false,
      'default' => '0000-00-00 00:00:00',
    ),
    'updatedon' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => false,
      'default' => '0000-00-00 00:00:00',
    ),
    'num' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '20',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
    'cost' => 
    array (
      'dbtype' => 'decimal',
      'precision' => '12,2',
      'phptype' => 'float',
      'null' => false,
      'default' => 0,
    ),
    'price' => 
    array (
      'dbtype' => 'decimal',
      'precision' => '12,2',
      'phptype' => 'float',
      'null' => false,
      'default' => 0,
    ),
    'discount' => 
    array (
      'dbtype' => 'decimal',
      'precision' => '12,2',
      'phptype' => 'float',
      'null' => false,
      'default' => 0,
    ),
    'status' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => true,
      'default' => 0,
    ),
    'city' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
    'address' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
    'phone' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '12',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
    'company' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
    'context' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => true,
      'default' => 'web',
    ),
    'comment' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'total' => 
    array (
      'dbtype' => 'int',
      'precision' => '3',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'properties' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'json',
      'null' => true,
    ),
  ),
  'indexes' => 
  array (
    'manager_id' => 
    array (
      'alias' => 'manager_id',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'manager_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'clietnt_id' => 
    array (
      'alias' => 'clietnt_id',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'clietnt_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'status' => 
    array (
      'alias' => 'status',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'status' => 
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
    'Products' => 
    array (
      'class' => 'brOrderProduct',
      'local' => 'id',
      'foreign' => 'order_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'OrderKit' => 
    array (
      'class' => 'brOrderKit',
      'local' => 'id',
      'foreign' => 'order_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
  'aggregates' => 
  array (
    'Manager' => 
    array (
      'class' => 'modUser',
      'local' => 'manager_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Client' => 
    array (
      'class' => 'modUser',
      'local' => 'client_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'ManagerProfile' => 
    array (
      'class' => 'modUserProfile',
      'local' => 'manager_id',
      'foreign' => 'internalKey',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'ClientProfile' => 
    array (
      'class' => 'modUserProfile',
      'local' => 'clietnt_id',
      'foreign' => 'internalKey',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
