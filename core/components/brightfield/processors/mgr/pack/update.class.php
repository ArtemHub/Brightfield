<?php

class BrightfieldPackUpdateProcessor extends modObjectUpdateProcessor {
    public $objectType = 'brKit';
    public $classKey = 'brKit';
    public $languageTopics = array('brightfield');
    public $permission = 'br_pack_update';

    public function beforeSave() {
        if (!$this->checkPermissions()) {
            return $this->modx->lexicon('access_denied');
        }
        return true;
    }

    public function beforeSet() {
        $id = (int)$this->getProperty('id');
        $title = trim($this->getProperty('title'));
        if (empty($id)) {
            return $this->modx->lexicon('brightfield_item_err_ns');
        }
        if (empty($title)) {
            $this->modx->error->addField('name', $this->modx->lexicon('brightfield_err_title'));
        }
        return parent::beforeSet();
    }
}
return 'BrightfieldPackUpdateProcessor';