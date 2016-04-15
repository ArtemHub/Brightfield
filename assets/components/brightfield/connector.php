<?php
/** @noinspection PhpIncludeInspection */
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CONNECTORS_PATH . 'index.php';
/** @var Brightfield $Brightfield */
$Br = $modx->getService('br', 'Brightfield', $modx->getOption('brightfield_core_path', null, $modx->getOption('core_path') . 'components/brightfield/') . 'model/brightfield/');
$modx->lexicon->load('brightfield:default');
// handle request
$corePath = $modx->getOption('brightfield_core_path', null, $modx->getOption('core_path') . 'components/brightfield/');
$path = $modx->getOption('processorsPath', $Br->config, $corePath . 'processors/');
$modx->request->handleRequest(array(
    'processors_path' => $path,
    'location' => '',
));