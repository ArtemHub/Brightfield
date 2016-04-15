<?php

class BrightfieldUserGetProcessor extends modObjectGetProcessor {
    public $objectType = 'modUserProfile';
    public $classKey = 'modUserProfile';
    public $primaryKeyField = 'internalKey';
    public $languageTopics = array('modextra:default');
    //public $permission = 'view';

    public function initialize() {
        $primaryKey = $this->getProperty($this->primaryKeyField,false);
        if (empty($primaryKey)) return $this->modx->lexicon($this->objectType.'_err_ns');
        $this->object = $this->modx->getObject($this->classKey, array($this->primaryKeyField => $primaryKey));
        if (empty($this->object)) return $this->modx->lexicon($this->objectType.'_err_nfs',array($this->primaryKeyField => $primaryKey));

        if ($this->checkViewPermission && $this->object instanceof modAccessibleObject && !$this->object->checkPolicy('view')) {
            return $this->modx->lexicon('access_denied');
        }
        return true;
    }

    public function process() {
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }
        return $this->cleanup();
    }

    public function cleanup() {
        return $this->success('', $this->iterate());
    }

    public function iterate() {
        $data = $this->object->toArray();
        return array(
            'city' => $data['city'],
            'company' => $data['comment'],
            'client_name' => $data['fullname'],
        );
    }
}
return 'BrightfieldUserGetProcessor';