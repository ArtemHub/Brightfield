<?php

abstract class BrightfieldMainController extends modExtraManagerController {

    public $br;

    public function initialize() {
        $corePath = $this->modx->getOption('brightfield_core_path', null, $this->modx->getOption('core_path') . 'components/brightfield/');
        require_once $corePath . 'model/brightfield/brightfield.class.php';
        $this->br = new Brightfield($this->modx);

        $this->modx->regClientCSS($this->br->config['cssUrl'] . 'mgr/brightfield.css');
        //$this->modx->regClientStartupScript($this->br->config['jsUrl'] . 'mgr/brightfield.js');
        parent::initialize();
    }

    public function getLanguageTopics() {
        return array('brightfield:default');
    }

    public function checkPermissions() {
        return true;
    }
}

class IndexManagerController extends BrightfieldMainController {

    public static function getDefaultController() {
        return 'order/create';
    }
}