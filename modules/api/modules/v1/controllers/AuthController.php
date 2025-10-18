<?php
namespace app\modules\api\modules\v1\controllers;

use yii\rest\Controller;
use Yii;
use app\models\User;

class AuthController extends Controller
{
    public function behaviors()
    {
        // Keep auth disabled here so clients can obtain a token
        return parent::behaviors();
    }

    /**
     * POST /api/v1/auth/login
     * Body (JSON): {"username":"admin","password":"secret123"}
     * Also supports email in "username" field.
     * Optional header: X-Tenant-Id: <id>
     */
    public function actionLogin()
    {
        $body = Yii::$app->request->bodyParams;
        $login = trim((string)($body['username'] ?? ''));
        $password = (string)($body['password'] ?? '');

        if ($login === '' || $password === '') {
            Yii::$app->response->statusCode = 400;
            return ['error' => 'username and password are required'];
        }

        // find by username OR email
        $user = User::find()
            ->where(['username' => $login])
            ->orWhere(['email' => $login])
            ->one();

        if (!$user || !$user->validatePassword($password)) {
            Yii::$app->response->statusCode = 401;
            return ['error' => 'Invalid credentials'];
        }

        // Optional: enforce tenant from header if provided
        $headerTenant = Yii::$app->request->headers->get('X-Tenant-Id');
        if ($headerTenant !== null && (int)$headerTenant !== (int)$user->tenant_id) {
            Yii::$app->response->statusCode = 403;
            return ['error' => 'Tenant mismatch'];
        }

        // For this prototype we reuse auth_key as a token
        return [
            'token' => $user->getAuthKey(),
            'user'  => [
                'id'        => $user->id,
                'username'  => $user->username,
                'email'     => $user->email,
                'tenant_id' => $user->tenant_id,
            ],
        ];
    }
}
