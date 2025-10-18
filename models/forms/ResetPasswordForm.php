<?php
namespace app\models\forms;

use yii\base\Model;
use app\models\User;

class ResetPasswordForm extends Model
{
    public string $password = '';
    private User $_user;

    public function __construct(User $user, $config=[])
    {
        $this->_user = $user;
        parent::__construct($config);
    }

    public function rules()
    {
        return [['password','required'], ['password','string','min'=>6]];
    }

    public function reset(): bool
    {
        $this->_user->setPassword($this->password);
        $this->_user->removePasswordResetToken();
        return $this->_user->save(false);
    }
}
