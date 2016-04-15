<?php

class BrightfieldOrderProductGetListProcessor extends modObjectGetListProcessor {
    public $objectType = 'brOrderProduct';
    public $classKey = 'brOrderProduct';
    public $permission = 'br_getlist';


    public function process() {
        $beforeQuery = $this->beforeQuery();
        if ($beforeQuery !== true) {
            return $this->failure($beforeQuery);
        }
        $data = $this->getData();
        $list = $this->iterate($data);
        return $this->outputArray($list,$data['total']);
    }

    public function getData() {
        if(!$order_id = $this->getProperty('order_id')) {
            return $this->failure('order_id not set!');
        };

        $data = array();

        $c = $this->modx->newQuery($this->classKey);
        $c->select($this->modx->getSelectColumns('brOrderProduct','brOrderProduct'));
        $c->where(array(
           'order_id' => $order_id,
            'kit_id' => 0
        ));


        $c = $this->prepareQueryBeforeCount($c);
        $data['total'] = $this->modx->getCount($this->classKey,$c);
        $c = $this->prepareQueryAfterCount($c);


        $data['results'] = $this->modx->getCollection($this->classKey,$c);
        return $data;
    }

    public function prepareQueryAfterCount(xPDOQuery $c) {
        $c->leftJoin('msProduct', 'Product', 'brOrderProduct.product_id = Product.id');
        $c->select($this->modx->getSelectColumns('msProduct','Product','',array('pagetitle','description','introtext')));

        $c->leftJoin('msProductData', 'ProductData', 'brOrderProduct.product_id = ProductData.id');
        $c->select($this->modx->getSelectColumns('msProductData','ProductData','',array('id','article','article_shinda','thumb','currency')));

        $c->sortby('brOrderProduct.id', 'DESC');

        return $c;
    }

    public function prepareRow(xPDOObject $object) {
        $product = $object->toArray();

        $result = array(
            'prod_id' => $product['id'],
            'prod_title' => $product['pagetitle'],
            'prod_desc' => $product['introtext'],
            'prod_code' => $product['article'],
            'prod_code_shinda' => $product['article_shinda'],
            'prod_thumb' => $product['thumb'],
            'price' => $product['price'],
            'cost' => $product['cost'],
            'discount' => $product['discount'],
            'count' => $product['count'],
        );
        $result['actions'][] = array(
            'cls' => '',
            'icon' => 'icon icon-trash-o action-gray',
            'action' => 'removeProduct',
            'button' => true,
            'menu' => true,
        );

        return $result;
    }
}
return 'BrightfieldOrderProductGetListProcessor';