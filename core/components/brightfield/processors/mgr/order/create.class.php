<?php

class BrightfieldOrderCreateProcessor extends modProcessor {
    public $object = null;
    public $permission = 'br_order_create';

    public function checkPermissions() {
        return !empty($this->permission) ? $this->modx->hasPermission($this->permission) : true;
    }

    public function process() {
        $this->object = $this->modx->newObject('brOrder');

        $this->object->fromArray(array(
            'manager_id' => $this->modx->getLoginUserID(),
            'createdon' => time(),
            'updatedon' => mktime(0,0,0,0,0,0),
            'num' => date('ym-dHs'),
            'status' => 1
        ));

        if(!$this->object->save()) {
            return $this->failure('Object create - ERROR!');
        }
        return $this->success();
    }

    public function success($msg = '',$object = null) {
        return json_encode(array(
            'success' => true,
            'id' => $this->object->id
        ));
    }
}
return 'BrightfieldOrderCreateProcessor';