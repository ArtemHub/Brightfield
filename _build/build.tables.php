<?php

if (!defined('MODX_BASE_PATH')) {
    require 'build.config.php';
}

require MODX_CORE_PATH . 'model/modx/modx.class.php';


$modx = new modX();
$modx->initialize('mgr');
$modx->getService('error', 'error.modError');
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget('ECHO');


$modx->addPackage('brightfield',MODX_CORE_PATH.'components/brightfield/model/');

/** @var xPDOManager $manager */
$manager = $modx->getManager();
/** @var xPDOGenerator $generator */
$modx->setOption(xPDO::OPT_AUTO_CREATE_TABLES, true);
//$manager->createObjectContainer('brKit');
//$manager->createObjectContainer('brPack');
//$manager->createObjectContainer('brOrder');
//$manager->createObjectContainer('brOrderKit');
//$manager->createObjectContainer('brOrderProduct');

return '<br />Table created.';