<?php

class BrightfieldPackRemoveProcessor extends modObjectProcessor {
    public $objectType = 'brKit';
    public $classKey = 'brKit';
    public $languageTopics = array('brightfield');
    public $permission = 'br_pack_remove';

    public function process() {
        $id = (int)$this->getProperty('id');
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }
        if (!$object = $this->modx->getObject($this->classKey, $id)) {
            return $this->failure($this->modx->lexicon('modextra_item_err_nf'));
        }
        $object->remove();
        return $this->success();
    }
}
return 'BrightfieldPackRemoveProcessor';