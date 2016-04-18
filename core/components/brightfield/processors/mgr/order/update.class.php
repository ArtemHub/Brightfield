<?php

class BrightfieldOrderUpdateProcessor extends modObjectUpdateProcessor{
    public $objectType = 'brOrder';
    public $classKey = 'brOrder';
    public $languageTopics = array('brightfield');
    public $id = null;
    public $permission = 'br_order_update';

    public function beforeSave()
    {
        if (!$this->checkPermissions()) {
            return $this->modx->lexicon('access_denied');
        }

        $this->id = $this->object->get('id');

        $this->object->set('updatedon', time());

        $c = $this->modx->newQuery('brOrderProduct');
        $c->where(array(
            'order_id' => $this->id
        ));
        $c->select('SUM(cost) as price');

        $price = $this->modx->getObject('brOrderProduct', $c)->get('price');
        $discount = $this->object->get('discount');
        if(!$price) {
            $price = 0; $cost = 0;
        }
        else {
            $cost = ($discount != 0) ? $price-($price/100*$discount) : $price;
        }

        $this->object->set('price', $price);
        $this->object->set('cost', $cost);
        $this->object->set('discount', $discount);

        return true;
    }
}
return 'BrightfieldOrderUpdateProcessor';