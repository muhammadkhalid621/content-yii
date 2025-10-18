<?php
namespace app\modules\api\modules\v1\controllers;

use app\models\File;
use yii\web\UploadedFile;
use Yii;

class FileController extends BaseApiController
{
    public $modelClass = 'app\models\File';

    // Example custom create action for uploads (kept from your earlier code)
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']); // we handle POST /files ourselves
        return $actions;
    }

    public function actionCreate()
    {
        if (!Yii::$app->user->can('file.upload')) {
            throw new \yii\web\ForbiddenHttpException('No permission to upload files.');
        }

        $upload = UploadedFile::getInstanceByName('file');
        if (!$upload) {
            Yii::$app->response->statusCode = 400;
            return ['error' => 'No file'];
        }

        $tenant = Yii::$app->tenant->id ?? 1;
        $dir = Yii::getAlias('@app/runtime/uploads/'.$tenant.'/'.date('Y/m'));
        if (!is_dir($dir)) @mkdir($dir, 0777, true);

        $name = uniqid().'.'.$upload->extension;
        $path = $dir.'/'.$name;

        if ($upload->saveAs($path)) {
            $f = new File();
            $f->tenant_id = $tenant;
            $f->original_name = $upload->name;
            $f->path = $path;
            $f->mime_type = $upload->type;
            $f->size = $upload->size;
            $f->created_by = Yii::$app->user->id ?? null;
            $f->created_at = $f->updated_at = time();
            $f->save(false);
            return ['id'=>$f->id, 'name'=>$f->original_name];
        }

        Yii::$app->response->statusCode = 500;
        return ['error'=>'Save failed'];
    }

    public function checkAccess($action, $model = null, $params = [])
    {
        if (in_array($action, ['delete','update','create'])) {
            if (!Yii::$app->user->can('file.upload') && !Yii::$app->user->can('file.delete')) {
                throw new \yii\web\ForbiddenHttpException('Insufficient permissions.');
            }
        }
    }
}
