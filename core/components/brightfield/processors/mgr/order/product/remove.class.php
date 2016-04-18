<?php

class BrightfieldOrderProductRemoveProcessor extends modObjectProcessor {
    public $objectType = 'brOrderProduct';
    public $classKey = 'brOrderProduct';
    public $languageTopics = array('brightfield');
    public $order_id = null;
    public $permission = 'br_remove';

    public function process() {
        $data = array(
            'product_id' => (int)$this->getProperty('product_id'),
            'order_id' => (int)$this->getProperty('order_id'),
            'kit_id' => (int)$this->getProperty('kit_id', 0),
            'pack_id' => (int)$this->getProperty('pack_id',0)
        );

        $this->order_id = $data['order_id'];

        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }
        if (!$object = $this->modx->getObject($this->classKey, $data)) {
            return $this->failure($this->modx->lexicon('modextra_item_err_nf'));
        }
        $object->remove();
        return $this->success();
    }

    public function updateOrder() {
        if (!$order = $this->modx->getObject('brOrder', $this->order_id)) {
            return $this->failure($this->modx->lexicon('empty_order_id'));
        }

        $c = $this->modx->newQuery($this->objectType);
        $c->where(array(
            'order_id' => $this->order_id
        ));
        $c->select('SUM(cost) as price');

        $price = $this->modx->getObject($this->objectType, $c)->get('price');
        if(!$price) {
            $price = 0; $cost = 0;
        }
        else {
            $cost = ($order->discount != 0) ? $price-($price/100*$order->discount) : $price;
        }

        $order->set('price', $price);
        $order->set('cost', $cost);
        $order->set('updatedon', time());

        $c = $this->modx->newQuery('brOrderProduct');
        $c->where(array(
            'order_id' => $this->order_id
        ));
        $c->select('SUM(count) as total');
        $total = $this->modx->getObject('brOrderProduct', $c)->get('total');
        $total = (!$total) ? 0 : $total;
        $order->set('total', $total);

        if($order->save() == false) {
            return $this->failure($this->modx->lexicon('order_err_save'));
        }

        $result = $order->toArray();
        $result['discount_price'] = $result['cost'] - $result['price'];
        return $result;
    }

    public function success() {
        return '{"success":true,"result":'.$this->modx->toJSON($this->updateOrder()).'}';
    }
}
return 'BrightfieldOrderProductRemoveProcessor';