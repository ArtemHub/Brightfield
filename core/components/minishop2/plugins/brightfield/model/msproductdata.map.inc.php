<?php
return array(
    'fields' => array(
        'title_english' => NULL,
        'title_moz' => NULL,
        'article_shinda' => NULL,
        'coefficient' => NULL,
        'currency' => NULL,
        'diameter' => NULL,
        'diameter_measure' => NULL,
        'length' => NULL,
        'length_measure' => NULL,
        'angle_viewing' => NULL,
        'angle_bend' => NULL,
        'content_commercial' => NULL,
    )
,'fieldMeta' => array(
        'title_english' => array(
            'dbtype' => 'varchar'
        ,'precision' => '255'
        ,'phptype' => 'string'
        ,'null' => false
        ,'default' => 'eur'
        ),
        'title_moz' => array(
            'dbtype' => 'varchar'
        ,'precision' => '255'
        ,'phptype' => 'string'
        ,'null' => false
        ,'default' => 'eur'
        ),
        'article_shinda' => array(
            'dbtype' => 'varchar'
        ,'precision' => '50'
        ,'phptype' => 'string'
        ,'null' => false
        ,'default' => 'eur'
        ),
        'coefficient' => array(
            'dbtype' => 'decimal'
        ,'precision' => '12,2'
        ,'phptype' => 'float'
        ,'null' => true
        ,'default' => NULL
        ),
        'currency' => array(
            'dbtype' => 'enum'
        ,'precision' => '\'usd\', \'eur\', \'uah\''
        ,'phptype' => 'string'
        ,'null' => false
        ,'default' => 'eur'
        ),
        'diameter' => array(
            'dbtype' => 'decimal'
        ,'precision' => '12,2'
        ,'phptype' => 'float'
        ,'null' => true
        ,'default' => NULL
        ),
        'diameter_measure' => array(
            'dbtype' => 'enum'
        ,'precision' => '\'mm\',\'fr\''
        ,'phptype' => 'string'
        ,'null' => true
        ,'default' => NULL
        ),
        'length' => array(
            'dbtype' => 'decimal'
        ,'precision' => '12,2'
        ,'phptype' => 'float'
        ,'null' => true
        ,'default' => NULL
        ),
        'length_measure' => array(
            'dbtype' => 'enum'
        ,'precision' => '\'mm\',\'cm\',\'m\''
        ,'phptype' => 'string'
        ,'null' => true
        ,'default' => NULL
        ),
        'angle_viewing' => array(
            'dbtype' => 'int'
        ,'precision' => '10'
        ,'phptype' => 'int'
        ,'null' => true
        ,'default' => NULL
        ),
        'angle_bend' => array(
            'dbtype' => 'int'
        ,'precision' => '10'
        ,'phptype' => 'int'
        ,'null' => true
        ,'default' => NULL
        ),
        'content_commercial' => array(
            'dbtype' => 'text'
        ,'phptype' => 'string'
        ,'null' => true
        ,'default' => NULL
        )
    )
,'indexes' => array(
        'article_shinda' => array (
            'alias' => 'article_shinda'
        ,'primary' => false
        ,'unique' => false
        ,'type' => 'BTREE'
        ,'columns' => array (
                'color' => array (
                    'length' => ''
                ,'collation' => 'A'
                ,'null' => false
                )
            )
        )
    )
);