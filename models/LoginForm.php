<?php

namespace app\models;

use Yii;
use yii\base\Model;

use app\models\Userdb;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = false;

    private $_user = false;

    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (Yii::$app->params['giristipi']!=1) { 
                $this->password=md5("alr".$this->password."en");
            }
            if (!$user || !$user->validatePassword($this->password) ) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    public function login()
    { 
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), 0);
        }   return false;
    }

    public function getUser()
    {
        if ($this->_user === false) {  
            if (Yii::$app->params['giristipi']==1) {
                $this->_user = \Edvlerblog\Adldap2\model\UserDbLdap::findByUsername($this->username);   //LDAP
            }else{
                $this->_user = Userdb::findByUsername($this->username) ;
            }
        }
        return $this->_user;
    }

}
