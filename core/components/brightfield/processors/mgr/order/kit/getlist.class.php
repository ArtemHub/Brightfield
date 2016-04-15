<?php

class BrightfieldOrderKitGetListProcessor extends modObjectGetListProcessor {
    public $objectType = 'brOrderKit';
    public $classKey = 'brOrderKit';
    public $permission = 'br_getlist';

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        if(!$order_id = $this->getProperty('order_id')) {
            return $this->failure('order_id not set!');
        };

        $c->leftJoin('modResource', 'Kit');
        $c->select($this->modx->getSelectColumns($this->objectType,$this->objectType));
        $c->select($this->modx->getSelectColumns('modResource','Kit','',array('pagetitle')));

        $c->where(array(
            $this->objectType.'.order_id' => $order_id
        ));

        return $c;
    }

    public function getData() {
        $data = array();

        $c = $this->modx->newQuery($this->classKey);
        $c = $this->prepareQueryBeforeCount($c);
        $data['total'] = $this->modx->getCount($this->classKey,$c);
        $c = $this->prepareQueryAfterCount($c);

        $data['results'] = $this->modx->getCollection($this->classKey,$c);
        return $data;
    }

    public function prepareRow(xPDOObject $object) {
        $row = array(
            'id' => $object->get('kit_id'),
            'title' => $object->get('pagetitle'),
        );
        return $row;
    }
}
return 'BrightfieldOrderKitGetListProcessor';