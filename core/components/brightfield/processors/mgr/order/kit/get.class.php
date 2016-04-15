<?php

class BrightfieldOrderKitGetProcessor extends modProcessor {
    public $order_id;
    public $kit_id;
    public $permission = 'br_getlist';

    public function checkPermissions() {
        return !empty($this->permission) ? $this->modx->hasPermission($this->permission) : true;
    }

    public function process() {
        $this->order_id = intval($this->getProperty('order_id'));
        $this->kit_id = intval($this->getProperty('kit_id'));

        $data = $this->getData();
        return $this->outputArray($data['results'], $data['total']);
    }

    public function getData() {
        $data = array();

        $packages = $this->getPackages();
        $products = $this->getProducts(array_keys($packages));
        $order_products = $this->getOrderProducts();

        $data['results'] = $this->prepareRow($packages, $products, $order_products);
        $data['total'] = count($data['results']);

        return $data;
    }

    public function prepareRow($packages, $products, $order_products) {
        $result = array();

        foreach($packages as $pack_id=>$package) {
            foreach ($products[$pack_id] as $product) {
                $res = array(
                    'pack_id' => $pack_id,
                    'pack_title' => $package['title'],
                    'prod_id' => $product['id'],
                    'prod_title' => $product['pagetitle'],
                    'prod_desc' => $product['introtext'],
                    'prod_code' => $product['article'],
                    'prod_code_shinda' => $product['article_shinda'],
                    'prod_thumb' => $product['thumb'],
                    'price' => $product['price'],
                    'cost' => '',
                    'discount' => '',
                    'count' => '',
                );
                if($order_products[$product['id']]) {
                    $res['price'] = $order_products[$product['id']]['price'];
                    $res['cost'] = $order_products[$product['id']]['cost'];
                    $res['discount'] = $order_products[$product['id']]['discount'];
                    $res['count'] = $order_products[$product['id']]['count'];
                    $res['active'] = true;
                    $res['actions'][] = array(
                        'cls' => '',
                        'icon' => 'icon icon-power-off action-green',
                        'action' => 'disableProduct',
                        'button' => true,
                        'menu' => true,
                    );
                }
                else {
                    $res['active'] = false;
                    $res['actions'][] = array(
                        'cls' => '',
                        'icon' => 'icon icon-power-off action-gray',
                        'action' => 'enableProduct',
                        'button' => true,
                        'menu' => true,
                    );
                }

                $result[] = $res;
            }
        }

        return $result;
    }

    public function getPackages() {
        $result = array();
        $c = $this->modx->newQuery('brKit');
        $c->where(array(
            'kitid' => $this->kit_id
        ));
        $c->sortby('menuindex', 'ASC');
        $objects = $this->modx->getCollection('brKit', $c);
        foreach($objects as $object) {
            $result[$object->id] = $object->toArray();
        }

        return $result;
    }

    public function getProducts($pack_ids) {
        $c = $this->modx->newQuery('brPack');
        $c->select($this->modx->getSelectColumns('brPack','brPack'));
        $c->where(array(
            'packid:IN' => $pack_ids
        ));
        $data['total'] = $this->modx->getCount('brPack',$c);

        $c->leftJoin('msProduct', 'Product', 'brPack.prodid = Product.id');
        $c->select($this->modx->getSelectColumns('msProduct','Product','',array('pagetitle','description','introtext')));

        $c->leftJoin('msProductData', 'ProductData', 'brPack.prodid = ProductData.id');
        $c->select($this->modx->getSelectColumns('msProductData','ProductData','',array('id','article','article_shinda','thumb','price','currency','coefficient')));


        $c->sortby('brPack.packid,brPack.menuindex', 'ASC');

        $result = array();
        $objects = $this->modx->getCollection('brPack',$c);
        foreach($objects as $object) {
            $result[$object->packid][$object->id] = $object->toArray();
        }
        return $result;
    }

    public function getOrderProducts() {
        $objects = $this->modx->getCollection('brOrderProduct', array(
            'order_id' => $this->order_id,
            'kit_id' => $this->kit_id
        ));

        $result = array();
        foreach($objects as $object) {
            $result[$object->product_id] = $object->toArray();
        }
        return $result;
    }
}
return 'BrightfieldOrderKitGetProcessor';