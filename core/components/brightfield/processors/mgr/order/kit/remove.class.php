<?php

class BrightfieldOrderKitRemoveProcessor extends modObjectProcessor {
    public $objectType = 'brOrderKit';
    public $classKey = 'brOrderKit';
    public $languageTopics = array('brightfield');
    public $order_id = null;
    public $permission = 'br_remove';

    public function process() {
        $kit_id = (int)$this->getProperty('kit_id');
        $this->order_id = (int)$this->getProperty('order_id');
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        if($kit_id === 0) {
            $this->modx->removeCollection('brOrderProduct', array(
                'kit_id' => 0,
                'order_id' => $this->order_id
            ));
        }
        else if (!$object = $this->modx->getObject($this->classKey, array('kit_id' => $kit_id, 'order_id' => $this->order_id))) {
            return $this->failure($this->modx->lexicon('modextra_item_err_nf'));
        }
        else {
            $object->remove();
        }
        return $this->success();
    }

    public function updateOrder() {
        if (!$order = $this->modx->getObject('brOrder', $this->order_id)) {
            return $this->failure($this->modx->lexicon('empty_order_id'));
        }

        $c = $this->modx->newQuery('brOrderProduct');
        $c->where(array(
            'order_id' => $this->order_id
        ));
        $c->select('SUM(cost) as price');

        $price = $this->modx->getObject('brOrderProduct', $c)->get('price');
        if(!$price) {
            $price = 0; $cost = 0;
        }
        else {
            $cost = ($order->discount != 0) ? $price-($price/100*$order->discount) : $price;
        }

        $order->set('price', $price);
        $order->set('cost', $cost);
        $order->set('updatedon', time());

        if($order->save() == false) {
            return $this->failure($this->modx->lexicon('order_err_save'));
        }

        $result = $order->toArray();
        $result['total'] = $this->modx->getCount('brOrderProduct', array('order_id' => $this->order_id));
        $result['discount_price'] = $result['cost'] - $result['price'];
        return $result;
    }

    public function success() {
        return '{"success":true,"result":'.$this->modx->toJSON($this->updateOrder()).'}';
    }
}
return 'BrightfieldOrderKitRemoveProcessor';