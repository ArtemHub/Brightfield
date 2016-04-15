<?php
/**
 * Get a list of Items
 */
class BrightfieldPackGetListProcessor extends modObjectGetListProcessor {
    public $objectType = 'brKit';
    public $classKey = 'brKit';
    public $permission = 'br_getlist';


    public function initialize() {
        return true;
    }

    public function process() {
        $beforeQuery = $this->beforeQuery();
        if ($beforeQuery !== true) {
            return $this->failure($beforeQuery);
        }
        $data = $this->getData();
        return $this->outputArray($data['results'],$data['total']);
    }

    public function getData() {
        $data = array();
        $kit_id = intval($this->getProperty('kit_id'));

        $packages = array();
        $c = $this->modx->newQuery('brKit');
        $c->where(array(
            'kitid' => $kit_id
        ));
        $c->sortby('menuindex', 'ASC');
        $objects = $this->modx->getCollection('brKit', $c);
        foreach($objects as $object) {
            $packages[$object->id] = $object->toArray();
        }

        $c = $this->modx->newQuery('brPack');
        $c->select($this->modx->getSelectColumns('brPack','brPack','',array('packid')));
        $c->where(array(
            'packid:IN' => array_keys($packages)
        ));
        $c = $this->prepareQueryBeforeCount($c);
        $data['total'] = $this->modx->getCount('brPack',$c);
        $c = $this->prepareQueryAfterCount($c);

        $c->leftJoin('msProduct', 'Product', 'brPack.prodid = Product.id');
        $c->select($this->modx->getSelectColumns('msProduct','Product','',array('pagetitle','description','introtext')));

        $c->leftJoin('msProductData', 'ProductData', 'brPack.prodid = ProductData.id');
        $c->select($this->modx->getSelectColumns('msProductData','ProductData','',array('id','article','article_shinda','thumb')));

        $c->sortby('brPack.packid,brPack.menuindex', 'ASC');

        $products = array();
        $objects = $this->modx->getCollection('brPack',$c);
        foreach($objects as $object) {
            $products[$object->packid][$object->id] = $object->toArray();
        }


        foreach($packages as $pack_id => $pack) {
            $res = array(
                'pack_id' => $pack_id,
                'pack_title' => $pack['title'],
                'prod_id' => '',
                'prod_title' => '',
                'prod_code' => '',
                'prod_code_shinda' => '',
                'prod_thumb' => ''
            );

            if(!$products[$pack_id]) {
                $data['results'][] = $res;
            }

            foreach($products[$pack_id] as $product) {
                $res['prod_id'] = $product['id'];
                $res['prod_title'] = $product['pagetitle'];
                $res['prod_desc'] = $product['introtext'];
                $res['prod_code'] = $product['article'];
                $res['prod_code_shinda'] = $product['article_shinda'];
                $res['prod_thumb'] = $product['thumb'];
                $data['results'][] = $res;
            }
        }
        $data['results'] = !empty($data['results']) ? $data['results'] : array();
        return  $data;
    }
}
return 'BrightfieldPackGetListProcessor';