<?php
namespace app\modules\api\modules\v1\controllers;

class UserController extends BaseApiController
{
    public $modelClass = 'app\models\User';

    public function checkAccess($action, $model = null, $params = [])
    {
        // Only admins manage users
        if (in_array($action, ['create','update','delete'])) {
            if (!\Yii::$app->user->can('user.manage')) {
                throw new \yii\web\ForbiddenHttpException('Admins only.');
            }
        }
    }
}
