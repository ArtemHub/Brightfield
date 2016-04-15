<?php

class BrightfieldOrderRemoveProcessor extends modObjectProcessor {
    public $objectType = 'brOrder';
    public $classKey = 'brOrder';
    public $languageTopics = array('brightfield');
    public $order_id = null;
    public $permission = 'br_order_remove';

    public function process() {
        $this->order_id = (int)$this->getProperty('id');
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        if (!$object = $this->modx->getObject($this->classKey, $this->order_id)) {
            return $this->failure('Order NOT FOUND!');
        }
        $object->remove();
        return $this->success();
    }
}
return 'BrightfieldOrderRemoveProcessor';