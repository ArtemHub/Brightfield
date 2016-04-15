<?php

class BrightfieldGeneratorOrderProcessor extends modObjectProcessor {
    public $id = null;
    public $data = null;
    public $permission = 'br_generate_pdf';

    public function process() {
        $path = MODX_ASSETS_PATH.'pdf/';
        $this->id = $this->getProperty('id');
        if(!$this->id) {
            return $this->failure('Order NOT FOUND!');
        }

        $object = $this->modx->getObject('brOrder', $this->id);
        $data = $param = $object->toArray();
        $param['name'] = $object->getOne('User')->username;
        $param['address'] = 'г.'.$object->city.', '.$object->address;
        $param['cost'] = '<div>Цена общая: '.$this->numberFormat($data['price']).' грн<br></div>';
        if($object->discount) {
            $param['cost'] .= '<div>Скидка: ' . $this->numberFormat(($data['cost'] - $data['price'])) . ' грн</div>';
        }
        $param['cost'].= '<div><b>ИТОГО: '.$this->numberFormat($data['cost']).' грн</b></div>';

        $info = $this->modx->getChunk('order.info', $param);

        $param = array();
        $param['order'] = $this->getHTML();
        $list = $this->modx->getChunk('order.list.wrapper', $param);


        $html = '<div class="order--wrapper">'.$info.$list.'</div>';
        $html = '<!DOCTYPE html><html lang="ru"><head><meta charset="utf-8"><title>Образец</title><link rel="stylesheet" href="http://brightfield.net/assets/components/brightfield/css/mgr/order.css"></head><body>'.$html.'</body></html>';

        file_put_contents($path.$data['num'].'.html',$html);
        return $this->success('', array('url' => MODX_ASSETS_URL.'pdf/'.$data['num']));
    }

    public function numberFormat($number) {
        return number_format($number, 2 , ',', '&nbsp;');
    }

    public function getHTML($id = 0, $type = 'kits') {
        $result = '';

        $this->data = $this->getData();

        foreach($this->data[$type] as $id=>$obj) {
            $page = '';
            $packs = $this->getPack($id);
            if(!empty($packs['html'])) {
                $page .= $this->modx->getChunk('insider.order.list.thead', array(
                    'title' => ($obj['pagetitle']) ? $obj['pagetitle'] : 'Дополнительно'
                ));

                $page .= $this->modx->getChunk('order.list.tbody', array(
                    'rows' => $packs['html'],
                ));


                $rows = '';
                $total = count($packs['images']);
                $total = ceil($total / 4) * 4;

                for ($i = 0; $i < $total; ++$i) {
                    if (!isset($packs['images'][$i])) {
                        $packs['images'][$i] = '<td>&nbsp;</td>';
                    }
                    if ($i == 0) {
                        $rows .= '<tr class="images-row">';
                        $rows .= $packs['images'][$i];
                    } else if ($i % 4 == 0 && $i != $total - 1) {
                        $rows .= '</tr>';
                        $rows .= '<tr class="images-row">';
                        $rows .= $packs['images'][$i];
                    } else if ($i % 4 == 0 && $i == $total - 1) {
                        $rows .= '</tr>';
                    } else {
                        $rows .= $packs['images'][$i];
                    }
                }
                $page = '<div class="disable-page-break">' . $page . '</div>';

                $page .= $this->modx->getChunk('order.list.images', array(
                    'rows' => $rows,
                ));

                $result .= $page;
            }
        }

        return $result;
    }
    public function getPack($kitid) {
        $result = array();

        foreach ($this->data['packs'] as $id=>$pack) {
            if($pack['kitid'] == $kitid) {
                $products = $this->getProducts($id);
                if(!empty($products['html'])) {
                    $result['images'] = (empty($result['images'])) ? $products['images'] : array_merge($result['images'], $products['images']);
                    if($pack['title']) {
                        $result['html'].= $this->modx->getChunk('insider.order.list.title', $pack);
                    }
                    $result['html'].= $products['html'];
                }
            }
        }

        return $result;
    }
    public function getProducts($packid) {
        $result = array();

        foreach ($this->data['products'] as $product) {
            if($product['pack_id'] == $packid) {
                $result['html'].= $this->modx->getChunk('insider.order.list.row', $product);
                $result['images'][] = $this->modx->getChunk('order.list.img', array(
                    'image' => 'http://brightfield.net'.$product['thumb'],
                    'article' => $product['article']
                ));
            }
        }

        return $result;
    }

    public function getData() {
        $result = array('kits' => array(), 'packs' => array(), 'products' => array());

        $c = $this->modx->newQuery('brOrderKit');
        $c->where(array(
            'brOrderKit.order_id' => $this->id
        ));
        $c->leftJoin('modResource', 'Kit');
        $c->select($this->modx->getSelectColumns('brOrderKit', 'brOrderKit'));
        $c->select($this->modx->getSelectColumns('modResource','Kit','',array('pagetitle')));
        $c->sortby('brOrderKit.id', 'ASC');

        $resources = $this->modx->getCollection('brOrderKit', $c);
        foreach($resources as $resource) {
            $result['kits'][$resource->kit_id] = $resource->toArray();
        }
        $result['kits'][0] = true;

        foreach(array_keys($result['kits']) as $id) {
            $c = $this->modx->newQuery('brKit');
            $c->where(array(
                'kitid' => $id
            ));
            $c->sortby('menuindex', 'ASC');
            $resources = $this->modx->getCollection('brKit', $c);
            foreach($resources as $resource) {
                $result['packs'][$resource->id] = $resource->toArray();
                $result['packs'][$resource->id]['kit_id'] = $id;
            }
        }
        $result['packs'][0]['kit_id'] = 0;

        $c = $this->modx->newQuery('brOrderProduct');
        $c->select($this->modx->getSelectColumns('brOrderProduct','brOrderProduct', '', array('id','product_id','kit_id','pack_id','price','cost','count','order_id','discount')));

        $c->leftJoin('msProduct', 'Product', 'brOrderProduct.product_id = Product.id');
        $c->select($this->modx->getSelectColumns('msProduct','Product','',array('pagetitle','description','introtext')));

        $c->leftJoin('msProductData', 'ProductData', 'brOrderProduct.product_id = ProductData.id');
        $c->select($this->modx->getSelectColumns('msProductData','ProductData','',array('article','article_shinda','thumb','image')));

        $c->where(array(
            array(
                'pack_id:IN' => array_keys($result['packs']),
                'OR:pack_id:=' => 0
            ), array(
                'order_id' => $this->id
            )
        ));

        $c->prepare();
        $sql = $c->toSQL();
        $q = $this->modx->prepare($sql);
        $q->execute();
        $objects = $q->fetchAll(PDO::FETCH_ASSOC);

        foreach($objects as $object) {
            $result['products'][$object['id']] = $object;
        }

        return $result;
    }
}
return 'BrightfieldGeneratorOrderProcessor';