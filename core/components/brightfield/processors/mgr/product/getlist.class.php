<?php

class BrightfieldProductGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'msProductData';
    public $field = null;
    public $permission = 'br_getlist';

    /** {@inheritDoc} */
    public function prepareQueryBeforeCount(xPDOQuery $c) {
        if ($query = $this->getProperty('query',null)) {
            $query = strtoupper(trim($query));
            $shinda = $this->getProperty('shinda',false);
            $this->field = (!$shinda) ? 'article' : 'article_shinda';

            $queryWhere = array(
                $this->field.':LIKE' => '%'.$query.'%'
            );
            $c->where($queryWhere);
        }
        return $c;
    }


    /** {@inheritDoc} */
    public function getData() {
        $data = array();

        /* query for chunks */
        $c = $this->modx->newQuery($this->classKey);
        $c = $this->prepareQueryBeforeCount($c);
        $data['total'] = $this->modx->getCount($this->classKey,$c);

        $data['results'] = $this->modx->getCollection($this->classKey, $c);

        return $data;
    }

    public function afterIteration(array $list) {
        $result = array();

        foreach ($list as $key=>$data) {
            $result[$key]['id'] = $data['id'];
            $result[$key]['value'] = $data[$this->field];
        }

        return $result;
    }
}

return 'BrightfieldProductGetListProcessor';