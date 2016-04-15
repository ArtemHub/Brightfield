<?php

class BrightfieldOrderKitAddProcessor extends modProcessor {
    public $objectType = 'brOrderKit';
    public $order_id = null;
    public $classKey = 'modResource';
    public $isset = '';
    public $permission = 'br_add';

    public function checkPermissions() {
        return !empty($this->permission) ? $this->modx->hasPermission($this->permission) : true;
    }

    public function process() {
        if(!$this->order_id = $this->getProperty('order_id')) {
            return $this->failure('Order id not set!');
        };
        if(!$id = $this->getProperty('id')) {
            return $this->failure('ids not set!');
        };
        $ids = explode(',',$id);
        if(!empty($ids)) {
            foreach($ids as $val) {
                if($obj = $this->modx->getObject($this->classKey, $val)) {
                    $this->insertKit($val);
                }
            }
        }

        return $this->cleanup();
    }

    public function insertKit($id) {
        $c = $this->modx->newQuery($this->objectType);
        $c->where(array(
            'order_id' => $this->order_id,
            'kit_id' => $id)
        );
        $c->leftJoin('modResource', 'Kit');
        $c->select($this->modx->getSelectColumns($this->objectType,$this->objectType));
        $c->select($this->modx->getSelectColumns('modResource','Kit','',array('pagetitle')));

        $object = $this->modx->getObject($this->objectType, $c);
        if(!empty($object)) {
            $this->isset.= (!empty($this->isset)) ? ', <b>'.$object->pagetitle.'</b>' : '<b>'.$object->pagetitle.'</b>';
            return false;
        }

        $object = $this->modx->newObject($this->objectType);
        $object->set('order_id', $this->order_id);
        $object->set('kit_id', $id);

        if($object->save() == false) {
            return false;
        }

        return true;
    }

    public function cleanup() {
        $msg = (!empty($this->isset)) ? '<div class="order-alert-isset">'.$this->isset.'<br>Перечисленные наборы были добавлены к заказу ранее.</div>' : '' ;
        return $this->success($msg);
    }
}
return 'BrightfieldOrderKitAddProcessor';