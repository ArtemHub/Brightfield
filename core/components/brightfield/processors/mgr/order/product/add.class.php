<?php

class BrightfieldOrderProductAddProcessor extends modObjectProcessor {
    public $objectType = 'brOrderProduct';
    public $classKey = 'brOrderProduct';
    public $languageTopics = array('brightfield');
    public $order_id = null;
    public $isset = null;
    public $permission = 'br_add';

    public function process() {
        $this->order_id = (int)$this->getProperty('order_id');
        $list = $this->getProperty('list', false);

        if(!$list) {
            $this->addOne();
        }
        else {
            $this->addMany();
        }

        return $this->success();
    }

    public function addOne() {
        $data = array(
            'order_id' => $this->order_id,
            'product_id' => (int)$this->getProperty('id', 0),
            'kit_id' => (int)$this->getProperty('kit_id', 0),
            'pack_id' => (int)$this->getProperty('pack_id', 0),
        );
        if(!$data['product_id']) {
            return $this->failure('Product ID not set!');
        }

        $this->addProduct($data);

        return true;
    }

    public function addMany() {
        $ids = $this->getProperty('id', false);
        $ids = explode(',', $ids);
        if(empty($ids)) {
            return $this->failure('Product id list is empty!');
        }

        foreach($ids as $value) {
            $data = array(
                'product_id' => $value,
                'order_id' => $this->order_id,
                'kit_id' => 0,
            );
            $this->addProduct($data);
        }

        return true;
    }

    public function addProduct($data) {
        if (!$object = $this->modx->getObject('msProductData', $data['product_id'])) {
            return $this->failure('Not found Product with this ID!');
        }

        if($this->isPresent($data)) {
            $this->isset.= (!empty($this->isset)) ? ', <b>'.$object->article_shinda.'</b>' : '<b>'.$object->article_shinda.'</b>';
            return false;
        }

        $data['price'] = $object->price;

        if($object->coefficient != 0) {
            $data['price'] = $data['price'] * $object->coefficient;
            $data['price'] = number_format($data['price'], 2, '.', '');
        }

        if($object->currency != 'uah') {
            $currency_rate = floatval($this->modx->getOption('currency_rate_'.$object->currency));
            $data['price'] = (($data['price']*100) * ($currency_rate * 100)) / 10000;
        }

        $data['cost'] = $data['price'];

        $data['archive'] = json_encode(array(
            'currency' => $object->currency,
            'currency_rate' => $currency_rate || null,
            'coefficient' => $object->coefficient,
            'price' => $object->price,
            'date' => time()
        ));

        $product_data  = $object;
        $object = $this->modx->newObject($this->classKey, $data);

        if($object->save() == false) {
             return $this->failure('Product '.$product_data->article_shinda.' NOT SAVE');
        }
        return true;
    }

    public function isPresent($data) {
        $obj = $this->modx->getObject('brOrderProduct', $data);
        return (!empty($obj)) ? true : false;
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

        return $order->toArray();
    }

    public function success($msg = '',$object = null) {
        $msg = (!empty($this->isset)) ? '<div class="order-alert-isset">'.$this->isset.'<br>Перечисленные товары были добавлены к заказу ранее.</div>' : '' ;
        $result = array(
            'success' => true,
            'message' => $msg,
            'result' => $this->updateOrder(),
        );
        return $this->modx->toJSON($result);
    }
}
return 'BrightfieldOrderProductAddProcessor';