<?php

class BrightfieldPackCreateProcessor extends modObjectCreateProcessor {
    public $objectType = 'brKit';
    public $classKey = 'brKit';
    public $languageTopics = array('brightfield');
    public $permission = 'br_pack_create';


    public function beforeSet() {
        $kit_id = trim($this->getProperty('kitid'));
        if (empty($kit_id)) {
            $this->modx->error->addField('new_pack', $this->modx->lexicon('brightfield_err_new_pack'));
        }
        else {
            $c = $this->modx->newQuery($this->objectType);
            $c->where(array(
                'kitid' => $kit_id
            ));
            $c->sortby('menuindex','DESC');
            $c->limit(1);

            $resource = $this->modx->getObject($this->objectType, $c);
            $this->setProperty('menuindex', ++$resource->menuindex);
        }

        return parent::beforeSet();
    }
}
return 'BrightfieldPackCreateProcessor';