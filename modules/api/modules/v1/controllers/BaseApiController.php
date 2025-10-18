<?php
namespace app\modules\api\modules\v1\controllers;

use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\AccessControl;
use yii\filters\Cors;
use Yii;

class BaseApiController extends ActiveController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        /**
         * 🔹 1. Add CORS Filter
         * This allows frontend or Postman requests from localhost or other origins.
         */
        $behaviors['corsFilter'] = [
            'class' => Cors::class,
            'cors'  => [
                'Origin' => ['http://localhost:8080', 'http://127.0.0.1:8080'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age' => 86400,
            ],
        ];

        /**
         * 🔹 2. Bearer Token Authentication
         * Requires Authorization: Bearer <token> header.
         */
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];

        /**
         * 🔹 3. Access Control
         * Allows only authenticated users (roles '@').
         */
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'], // must be logged in via token
                ],
            ],
        ];

        /**
         * Move authenticator after CORS filter so OPTIONS requests bypass auth.
         */
        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);
        $behaviors['authenticator'] = $auth;

        return $behaviors;
    }

    /**
     * 🔹 4. Shared Access Check for write operations
     * Controllers can override this if they need custom permissions.
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        // Only protect write actions (create, update, delete)
        if (in_array($action, ['create', 'update', 'delete'])) {
            if (!Yii::$app->user->can('content.manage')) {
                throw new \yii\web\ForbiddenHttpException('You are not allowed to perform this action.');
            }
        }
    }
}
