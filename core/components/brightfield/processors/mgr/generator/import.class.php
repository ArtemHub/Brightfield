<?php

class BrightfieldGeneratorImportProcessor extends modObjectProcessor {
    public $data = array();
    public $products = array();
    public $permission = 'br_import';

    public function process() {
        $this->products = $this->getProducts();

        $uploaddir = MODX_ASSETS_PATH.'components/brightfield/import/';
        $uploadfile = $uploaddir . basename($_FILES['file']['name']);

        if(!move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
            return $this->failure('Ошибка загрузки файла!');
        }

        $file = file($uploadfile);

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

        $this->import();

        unlink($uploadfile);

        return $this->success();
    }

    public function import() {
        $table = $this->modx->getTableName('msProductData');
        foreach ($this->data as $data) {
            if(!$this->products[$data['article_shinda']]) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, $data['article_shinda'].' - товар не найден');
            }
            else {
                $sql = 'UPDATE '.$table.' SET price='.$data['price'].',currency=\''.strtolower($data['currency']).'\',coefficient='.$data['coefficient'].' WHERE article_shinda=\''.$data['article_shinda'].'\'';
                $q = $this->modx->prepare($sql);
                $q->execute();
                $this->modx->log(modX::LOG_LEVEL_INFO,$data['article_shinda'].' - обновлен<br>');
            }
        }
        $this->modx->log(modX::LOG_LEVEL_INFO,'COMPLETED');
    }

    public function getProducts() {
        $result = array();
        $c = $this->modx->newQuery('msProduct');
        $c->leftJoin('msProductData', 'msProductData', 'msProductData.id = msProduct.id');
        $c->select($this->modx->getSelectColumns('msProduct','msProduct'));
        $c->select($this->modx->getSelectColumns('msProductData','msProductData'));
        $c->sortby('msProductData.article_shinda', 'ASC');

        $products = $this->modx->getCollection('msProduct');
        foreach($products as $product) {
            $result[$product->get('article_shinda')] = $product->toArray();
        }
        return $result;
    }
}
return 'BrightfieldGeneratorImportProcessor';