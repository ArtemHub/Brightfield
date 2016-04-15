<?php

class BrightfieldOrderProductUpdateProcessor extends modObjectProcessor {
    public $objectType = 'brOrderProduct';
    public $classKey = 'brOrderProduct';
    public $order_id = null;
    public $kit_id = null;
    public $product_id = null;
    public $count= null;
    public $discount= null;
    public $languageTopics = array('brightfield');
    public $permission = 'br_update';


    public function process() {
        if(!$data = $this->getProperty('data', false)) {
            return $this->failure('DATA Not Set!');
        }
        $data = json_decode($data, true);

        $this->order_id = (int)$data['order_id'];
        $this->kit_id = (int)$data['kit_id'];
        $this->product_id = (int)$data['prod_id'];
        $this->count = (int)$data['count'];
        $this->discount = (int)$data['discount'];

        $where = array(
            'order_id' => $this->order_id,
            'kit_id' => $this->kit_id,
            'product_id' => $this->product_id,
        );
        if (!$object = $this->modx->getObject($this->objectType, $where)) {
            return $this->failure('Not found Order Product!');
        }

        $data = array();
        $product = $this->modx->getObject('msProductData', $this->product_id);

        $data['price'] = $product->price;

        if($product->coefficient != 0) {
            $data['price'] = $data['price'] * $product->coefficient;
            $data['price'] = number_format($data['price'], 2, '.', '');
        }
        if($this->discount) {
            $data['price'] = $data['price']-($data['price']/100*$this->discount);
        }

        if($product->currency != 'uah') {
            $currency_rate = floatval($this->modx->getOption('currency_rate_'.$product->currency));
            $data['price'] = (($data['price']*100) * ($currency_rate * 100)) / 10000;
        }

        if($this->count) {
            $data['cost'] = $data['price']*$this->count;
        }
        else {
            $this->count = 1;
            $data['cost'] = $data['price'];
        }

        $object->set('price', $data['price']);
        $object->set('cost', $data['cost']);
        $object->set('discount', $this->discount);
        $object->set('count', $this->count);

        $object->save();

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

        if($order->save() == false) {
            return $this->failure($this->modx->lexicon('order_err_save'));
        }

        $result = $order->toArray();
        $result['total'] = $this->modx->getCount('brOrderProduct', array('order_id' => $this->order_id));
        $result['discount_price'] = $result['cost'] - $result['price'];
        return $result;
    }

    public function success($msg = '',$object = null) {
        $result = array(
            'success' => true,
            'message' => '',
            'result' => $this->updateOrder(),
        );
        return $this->modx->toJSON($result);
    }
}
return 'BrightfieldOrderProductUpdateProcessor';