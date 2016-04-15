<?php

class BrightfieldPackCodeparserProcessor extends modProcessor {
    public $objectType = 'brPack';
    public $classKey = 'brPack';
    public $languageTopics = array('brightfield');
    public $permission = 'br_pack_add';

    public function checkPermissions() {
        return !empty($this->permission) ? $this->modx->hasPermission($this->permission) : true;
    }


    public function process() {
        $packid = (int)$this->getProperty('pack_id');
        $list = explode(',', $this->getProperty('codelist'));
        if(!count($list)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'Не определено ни одного кода товара!');
        }
        else {
            $this->modx->log(modX::LOG_LEVEL_INFO, 'Определено кодов: ' . count($list).'<hr>');
        }

        $menuindex = $this->getMenuindex(array(
            'packid' => $packid
        ));

        $c = $this->modx->newQuery('brPack');
        $c->select($this->modx->getSelectColumns('brPack','brPack','',array('packid')));

        $c->leftJoin('msProductData', 'ProductData', 'brPack.prodid = ProductData.id');
        $c->select($this->modx->getSelectColumns('msProductData','ProductData','',array('id','article','article_shinda','thumb')));

        $c->where(array(
            'ProductData.article_shinda:IN' => $list,
            'brPack.packid' => $packid
        ));

        $resources = $this->modx->getCollection('brPack', $c);
        foreach($resources as $resource) {
            unset($list[array_search($resource->article_shinda, $list)]);
            $this->modx->log(modX::LOG_LEVEL_WARN, $resource->article_shinda.' - уже добавлен');
        }

        $count = 0;
        if(!empty($list)) {
            $c = $this->modx->newQuery('msProductData');
            $c->where(array(
                'article_shinda:IN' => $list,
            ));
            $resources = $this->modx->getCollection('msProductData', $c);
            foreach ($resources as $resource) {
                unset($list[array_search($resource->article_shinda, $list)]);
                $obj = $this->modx->newObject('brPack');
                $obj->set('packid', $packid);
                $obj->set('prodid', $resource->id);
                $obj->set('menuindex', ++$menuindex);
                $obj->save();

                $this->modx->log(modX::LOG_LEVEL_WARN, $resource->article_shinda . ' - Ок');
                ++$count;
            }
        }

        $this->modx->log(modX::LOG_LEVEL_WARN, '<hr>Всего добавлено товаров: '.$count);
        if(!empty($list)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, '<hr>Товары с кодом: <b>'.(implode(', ',$list)).'</b> не найдены');
        }


        $this->modx->log(modX::LOG_LEVEL_INFO,'COMPLETED');
    }

    public function getMenuindex($where) {
        $c = $this->modx->newQuery($this->objectType);
        $c->where($where);
        $c->sortby('menuindex','DESC');
        $c->limit(1);

        $resource = $this->modx->getObject($this->objectType, $c);
        return $resource ? $resource->menuindex : 0;
    }
}
return 'BrightfieldPackCodeparserProcessor';