<?php
namespace app\modules\api\modules\v1\controllers;

class AuditLogController extends BaseApiController
{
    public $modelClass = 'app\models\AuditLog';

    public function checkAccess($action, $model = null, $params = [])
    {
        // View only for users with audit.view
        if (in_array($action, ['index','view'])) {
            if (!\Yii::$app->user->can('audit.view')) {
                throw new \yii\web\ForbiddenHttpException('Not allowed to view audit logs.');
            }
        } else {
            // No create/update/delete for audit logs via API
            throw new \yii\web\ForbiddenHttpException('This resource is read-only.');
        }
    }
}
