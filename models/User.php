<?php

namespace app\models;

use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;
use Yii;

class User extends BaseTenantActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return 'user';
    }

    public function behaviors()
    {
        return [TimestampBehavior::class];
    }

    public function rules()
    {
        return [
            [['username', 'email', 'password_hash', 'auth_key'], 'required'],
            [['username', 'email'], 'unique'],
            ['email', 'email'],
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::find()->andWhere(['auth_key' => $token])->one();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    public static function findByUsername($username)
    {
        return static::find()->andWhere(['username' => $username])->one();
    }

    public function setPassword(?string $password): void
    {
        if (empty($password)) {
            return; // skip if null or empty string
        }
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
    public function generatePasswordResetToken(): void
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }
    public static function findByPasswordResetToken(string $token): ?self
    {
        if (empty($token)) return null;
        $parts = explode('_', $token);
        $ts = (int)end($parts);
        // token valid 1 hour
        if ($ts + 3600 < time()) return null;
        return static::find()->andWhere(['password_reset_token' => $token])->one();
    }
    public function removePasswordResetToken(): void
    {
        $this->password_reset_token = null;
    }
}
