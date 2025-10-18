<?php
namespace app\modules\api\modules\v1\controllers;

class ContentController extends BaseApiController
{
    public $modelClass = 'app\models\Content';

    // Optional: customize Content-specific checks
    public function checkAccess($action, $model = null, $params = [])
    {
        if (in_array($action, ['create','update','delete'])) {
            // Editors and Admins can write; Viewers read-only
            if (!\Yii::$app->user->can('content.create') &&
                !\Yii::$app->user->can('content.update') &&
                !\Yii::$app->user->can('content.delete')) {
                throw new \yii\web\ForbiddenHttpException('Insufficient permissions.');
            }
        }
    }
}
