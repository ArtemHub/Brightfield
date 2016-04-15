<?php

class BrightfieldOrderGetTabsProcessor extends modObjectGetListProcessor {
    public $objectType = 'brOrderKit';
    public $classKey = 'brOrderKit';
    public $order_id = null;
    public $permission = 'br_getlist';

    public function process() {
        $beforeQuery = $this->beforeQuery();
        if ($beforeQuery !== true) {
            return $this->failure($beforeQuery);
        }
        $data = $this->getData();
        $list = $this->iterate($data);
        $data['total'] = count($list);
        return $this->outputArray($list,$data['total']);
    }

    public function afterIteration(array $list) {
        $c = $this->modx->newQuery('brOrderProduct');
        $c->where(array(
            'kit_id' => 0,
            'order_id' => $this->order_id
        ));
        $total = $this->modx->getCount('brOrderProduct',$c);
        if($total != 0) {
            array_unshift($list, array(
                'id' => 0,
                'title' => ' Без категории',
                'xtype' => 'br-order-grid-product',
            ));
        }

        return $list;
    }

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        if(!$this->order_id = $this->getProperty('order_id')) {
            return $this->failure('order_id not set!');
        };

        $c->leftJoin('modResource', 'Kit');
        $c->select($this->modx->getSelectColumns($this->objectType,$this->objectType));
        $c->select($this->modx->getSelectColumns('modResource','Kit','',array('pagetitle', 'menutitle', 'link_attributes')));

        $c->where(array(
            $this->objectType.'.order_id' => $this->order_id
        ));
        $c->sortby('id', 'ASC');

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
            'title' => '<div class="br-tab-label">'.$object->get('menutitle').'<span>'.$object->get('link_attributes').'</span></div>',
            'xtype' => 'br-order-grid-kit',
        );
        return $row;
    }
}
return 'BrightfieldOrderGetTabsProcessor';