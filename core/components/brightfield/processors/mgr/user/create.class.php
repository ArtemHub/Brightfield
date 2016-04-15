<?php

class BrightfieldUserCreateProcessor extends modProcessor {
    public $object = null;
    public $user_group = 3;
    public $id = null;
    public $permission = 'br_add_phone';

    public function checkPermissions() {
        return !empty($this->permission) ? $this->modx->hasPermission($this->permission) : true;
    }

    public function process() {
        if (!$this->checkPermissions()) {
            return $this->modx->lexicon('access_denied');
        }

        $phone = $this->getProperty('phone');
        if(strlen($phone) != 12) {
            return $this->failure('Указаный номер не соответствует стандарту.');
        }
        if($user = $this->modx->getObject('modUser', array('username' => $phone))) {
            return $this->failure('Указаный номер уже есть в базе.');
        }

        $user = $this->modx->newObject('modUser');

        $user->fromArray(array(
            'username' => $phone,
            'active' => 1,
            'primary_group' => $this->user_group,
            'password' => $this->generatePassword(8)
        ));
        $user->save();

        $this->id = $user->get('id');

        $userProfile = $this->modx->newObject('modUserProfile');
        $userProfile->fromArray(array(
            'internalKey' => $this->id,
            'fullname' => $this->getProperty('fullname'),
            'email' => $this->getProperty('email', ''),
            'city' => $this->getProperty('city', ''),
            'comment' => $this->getProperty('company', ''),
            'blocked' => 0,
            'blockeduntil' => 0,
            'blockedafter' => 0,
        ));
        $userProfile->save();

        $Member = $this->modx->newObject('modUserGroupMember');
        $Member->set('user_group', $this->user_group);
        $Member->set('member', $this->id);
        $Member->set('role', 1);
        $Member->set('rank', 0);
        $Member->save();

        return $this->success();
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
return 'BrightfieldUserCreateProcessor';