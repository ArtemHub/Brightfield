<?php

class BrightfieldRmproductProcessor extends modObjectProcessor {
    public $objectType = 'brPack';
    public $classKey = 'brPack';
    public $languageTopics = array('brightfield');
    public $permission = 'br_pack_remove_product';

    public function process() {
        $pack_id = (int)$this->getProperty('pack_id');
        $prod_id = (int)$this->getProperty('prod_id');
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }
        if (!$object = $this->modx->getObject($this->classKey, array(
            'packid' => $pack_id,
            'prodid' => $prod_id
        ))) {
            return $this->failure($this->modx->lexicon('modextra_item_err_nf'));
        }
        $object->remove();
        return $this->success();
    }
}
return 'BrightfieldRmproductProcessor';