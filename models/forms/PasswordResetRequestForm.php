<?php
namespace app\models\forms;

use yii\base\Model;
use app\models\User;
use Yii;

class PasswordResetRequestForm extends Model
{
    public string $email = '';

    public function rules()
    {
        return [['email','required'], ['email','email']];
    }

    public function sendEmail(): bool
    {
        $user = User::find()->andWhere(['email'=>$this->email])->one();
        if (!$user) return false;

        $user->generatePasswordResetToken();
        $user->save(false);

        return Yii::$app->mailer->compose()
            ->setTo($user->email)
            ->setSubject('Password reset')
            ->setTextBody("Use this link to reset:\n" .
                Yii::$app->urlManager->createAbsoluteUrl(['/site/reset-password','token'=>$user->password_reset_token]))
            ->send();
    }
}
