<?php

namespace app\models;

use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password;
    private $_user = false;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
        ];
    }

    public function login()
    {
        if ($this->validate()) {
            $user = $this->getUser();

        
            if ($user && Yii::$app->security->validatePassword($this->password, $user->password_hash)) {
                Yii::$app->user->login($user);

                
                $session = Yii::$app->session;
                $session->open();
                $session->set('username', $user->username);
                $session->set('user_id', $user->id);

                return true;
            }
        }
        Yii::debug($_SESSION, 'session');
        return false;
    }

    protected function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findOne(['username' => $this->username]);
        }
        return $this->_user;
    }
}
