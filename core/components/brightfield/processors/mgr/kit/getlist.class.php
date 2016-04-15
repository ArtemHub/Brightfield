<?php
/**
 * Get a list of Items
 */
class BrightfieldKitGetListProcessor extends modObjectGetListProcessor {
    public $objectType = 'modResource';
    public $classKey = 'modResource';
    public $field = null;
    public $permission = 'br_getlist';


    public function prepareQueryBeforeCount(xPDOQuery $c) {
        if ($query = $this->getProperty('query',null)) {
            $query = strtoupper(trim($query));

            $shinda = $this->getProperty('shinda',false);
            $this->field = (!$shinda) ? 'menutitle' : 'link_attributes';

            $c->where(array(
                $this->field.':LIKE' => "%{$query}%",
                'template' => 3
            ));
        }
        return $c;
    }

    public function getData() {
        $data = array();
        $limit = intval($this->getProperty('limit'));
        $start = intval($this->getProperty('start'));

        /* query for chunks */
        $c = $this->modx->newQuery($this->classKey);
        $c = $this->prepareQueryBeforeCount($c);
        $data['total'] = $this->modx->getCount($this->classKey,$c);
        $c = $this->prepareQueryAfterCount($c);

        $c->prepare();
        $c->stmt->execute();
        $data['results'] = $c->stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    public function iterate(array $data) {
        $list = array();
        $list = $this->beforeIteration($list);
        $this->currentIndex = 0;
        /** @var xPDOObject|modAccessibleObject $object */
        foreach ($data['results'] as $object) {
            if ($this->checkListPermission && $object instanceof modAccessibleObject && !$object->checkPolicy('list')) continue;
            $objectArray = $object;
            if (!empty($objectArray) && is_array($objectArray)) {
                $list[] = $objectArray;
                $this->currentIndex++;
            }
        }
        $list = $this->afterIteration($list);
        return $list;
    }

    public function afterIteration(array $list) {
        $result = array();

        foreach ($list as $key=>$data) {
            $result[$key]['id'] = $data[$this->classKey.'_id'];
            $result[$key]['value'] = $data[$this->classKey.'_'.$this->field];
        }

        return $result;
    }
}
return 'BrightfieldKitGetListProcessor';