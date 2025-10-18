<?php
namespace app\models\forms;

use yii\base\Model;
use app\models\User;
use Yii;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
        ];
    }

    public function login()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            if ($user && $user->validatePassword($this->password)) {
                return Yii::$app->user->login($user, $this->rememberMe ? 3600*24*30 : 0);
            }
            $this->addError('password', 'Incorrect username or password.');
        }
        return false;
    }

    protected function getUser()
    {
    if ($this->_user === null) {
        $u = User::find()
            ->where(['username' => $this->username])
            ->orWhere(['email' => $this->username])
            ->one();
        $this->_user = $u;
    }
    return $this->_user;
}
}
