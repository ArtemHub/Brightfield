<?php

class BrightfieldOrderGetListProcessor extends modObjectGetListProcessor {
    public $objectType = 'brOrder';
    public $classKey = 'brOrder';
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'DESC';
    public $permission = 'br_getlist';


    public function prepareQueryBeforeCount(xPDOQuery $c) {
        if ($query = $this->getProperty('query',null)) {
            $query = trim($this->getProperty('query'));
            $query = strtoupper($query);
            $c->where(array(
                'num:LIKE' => "%{$query}%",
            ));
        }
        return $c;
    }


    public function prepareQueryAfterCount(xPDOQuery $c) {
        $c->leftJoin('modUser', 'Manager');
        $c->select('Manager.username AS manager_username');

        $c->leftJoin('modUserProfile', 'ManagerProfile');
        $c->select('ManagerProfile.fullname AS manager_fullname');

        $c->leftJoin('modUser', 'Client');
        $c->select('Client.username AS client_username');

        /*
        $c->leftJoin('modUserProfile', 'ClientProfile');
        $c->select('ClientProfile.fullname AS client_fullname');
        */

        $c->select($this->modx->getSelectColumns('brOrder','brOrder'));



        return $c;
    }

    public function prepareRow(xPDOObject $object) {
        $array = $object->toArray();
        $array['actions'] = array();
        // Edit
        $array['actions'][] = array(
            'cls' => '',
            'icon' => 'icon icon-edit',
            'title' => 'Изменить',
            'action' => 'updateOrder',
            'button' => true,
            'menu' => true,
        );
        // Remove
        $array['actions'][] = array(
            'cls' => '',
            'icon' => 'icon icon-trash-o action-red',
            'title' => 'Удалить',
            'action' => 'removeOrder',
            'button' => true,
            'menu' => true,
        );
        return $array;
    }

    /*
        public function getManager() {
            return $this->modx->getObject('modUserProfile', array('internalKey' => $this->modx->getLoginUserID()))->toArray();
        }
     */
}
return 'BrightfieldOrderGetListProcessor';