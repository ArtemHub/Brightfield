<?php

class BrightfieldGeneratorImportProcessor extends modObjectProcessor {
    public $data = array();
    public $products = array();
    public $permission = 'br_import';

    public function process() {
        $this->products = $this->getProducts();

        $file = file(MODX_ASSETS_PATH.'import/db.csv');
        $this->data = array_map(function($value) {
            $result =  explode('|', $value);
            return array(
                'article' => trim($result[0]),
                'article_shinda' => trim($result[1]),
                'price' => trim($result[2]),
                'currency' => trim($result[3]),
                'coefficient' => trim($result[4]),
            );
        }, $file);

        foreach ($this->data as $data) {

        }

        $this->import();

        return $this->success();
    }

    public function import() {
        $table = $this->modx->getTableName('msProductData');
        foreach ($this->data as $data) {
            if(!$this->products[$data['article_shinda']]) {
                echo $data['article_shinda'].' - товар не найден<br>';
            }
            else {
                $sql = 'UPDATE '.$table.' SET price='.$data['price'].',currency=\''.strtolower($data['currency']).'\',coefficient='.$data['coefficient'].' WHERE article_shinda=\''.$data['article_shinda'].'\'';
                $q = $this->modx->prepare($sql);
                $q->execute();
                echo $data['article_shinda'].' - обновлен<br>';
            }
        }
    }

    public function getProducts() {
        $result = array();
        $c = $this->modx->newQuery('msProduct');
        $c->leftJoin('msProductData', 'ProductData');
        $c->select($this->modx->getSelectColumns('msProduct','msProduct'));
        $c->select($this->modx->getSelectColumns('msProductData','ProductData'));
        $c->sortby('ProductData.article_shinda', 'ASC');

        $products = $this->modx->getCollection('msProduct');
        foreach($products as $product) {
            $result[$product->get('article_shinda')] = $product->toArray();
        }
        return $result;
    }
}
return 'BrightfieldGeneratorImportProcessor';