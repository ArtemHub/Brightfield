<?php

class BrightfieldOrderUpdateProcessor extends modObjectUpdateProcessor{
    public $objectType = 'brOrder';
    public $classKey = 'brOrder';
    public $languageTopics = array('brightfield');
    public $user_group = 3;
    public $client_id = null;
    public $permission = 'br_order_update';

    public function beforeSave()
    {
        if (!$this->checkPermissions()) {
            return $this->modx->lexicon('access_denied');
        }

        $user_id = $this->object->get('client_id');
        if(strlen($user_id) == 12) {
            if(!$user = $this->modx->getObject('modUser', array('username' => $user_id))) {
                $user = $this->modx->newObject('modUser');

                $user->fromArray(array(
                    'username' => $user_id,
                    'active' => 1,
                    'primary_group' => $this->user_group,
                    'password' => $this->generatePassword(8)
                ));
                $user->save();
                $this->client_id = $user->get('id');

                $userProfile = $this->modx->newObject('modUserProfile');
                $userProfile->fromArray(array(
                    'fullname' => $this->getProperty('client_name'),
                    'internalKey' => $this->client_id,
                    'email' => '',
                    'blocked' => 0,
                    'blockeduntil' => 0,
                    'blockedafter' => 0,
                ));
                $userProfile->save();

                $Member = $this->modx->newObject('modUserGroupMember');
                $Member->set('user_group', $this->user_group);
                $Member->set('member', $user->get('id'));
                $Member->set('role', 1);
                $Member->set('rank', 0);
                $Member->save();
            }
            else {
                $this->addFieldError('client_id','Пользователь с таким номером телефона уже есть в базе!');
            }

            $this->object->set('client_id', $this->client_id);
        }
        else if(!$user_id) {
            $this->addFieldError('client_id','Неверно указан номер телефона!');
        }

        $this->object->set('updatedon', time());

        $c = $this->modx->newQuery('brOrderProduct');
        $c->where(array(
            'order_id' => $this->object->get('id')
        ));
        $c->select('SUM(cost) as price');

        $price = $this->modx->getObject('brOrderProduct', $c)->get('price');
        $discount = $this->object->get('discount');
        if(!$price) {
            $price = 0; $cost = 0;
        }
        else {
            $cost = ($discount != 0) ? $price-($price/100*$discount) : $price;
        }

        $this->object->set('price', $price);
        $this->object->set('cost', $cost);
        $this->object->set('discount', $discount);

        return true;
    }

    public function generatePassword($length = 10) {
        $allowable_characters = 'abcdefghjkmnpqrstuvxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $ps_len = strlen($allowable_characters);
        srand((double) microtime() * 1000000);
        $pass = '';
        for ($i = 0; $i < $length; $i++) {
            $pass .= $allowable_characters[mt_rand(0, $ps_len -1)];
        }
        return $pass;
    }
}
return 'BrightfieldOrderUpdateProcessor';