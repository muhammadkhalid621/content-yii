<?php

namespace app\controllers;

use yii\web\Controller;
use yii\web\UploadedFile;
use yii\data\ActiveDataProvider;
use Yii;
use app\models\File;

class FileController extends Controller
{
    public function actionIndex()
    {
        $searchModel  = new \app\models\search\FileSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render('index', compact('searchModel', 'dataProvider'));
    }

    public function actionUpload()
    {
        // Gate: must be logged in (AccessControl handles this)
        $request = Yii::$app->request;

        if ($request->isGet) {
            // Show the form
            return $this->render('upload');
        }

        if (!$request->isPost) {
            return $this->asJson(['error' => 'Invalid method. Use POST with multipart/form-data.']);
        }

        // IMPORTANT: the input name must be "upload"
        $file = UploadedFile::getInstanceByName('upload');
        if (!$file) {
            return $this->asJson(['error' => 'No file received. Ensure the input name is "upload" and the form has enctype="multipart/form-data".']);
        }

        // Resolve tenant (fallback to 1)
        $tenantId = Yii::$app->tenant->id ?? (int)Yii::$app->session->get('tenant_id', 1);
        if (!$tenantId) $tenantId = 1;

        // Build target dir: @app/runtime/uploads/<tenant>/Y/m
        $baseDir = Yii::getAlias('@app/runtime/uploads');
        $targetDir = $baseDir . '/' . $tenantId . '/' . date('Y/m');

        // Create dirs if needed
        if (!is_dir($targetDir) && !@mkdir($targetDir, 0777, true)) {
            return $this->asJson(['error' => "Failed to create directory: $targetDir"]);
        }

        // Check writability
        if (!is_writable($targetDir)) {
            return $this->asJson(['error' => "Directory not writable: $targetDir"]);
        }

        // Save with unique name
        $safeExt = preg_replace('~[^a-z0-9]+~i', '', $file->extension);
        $filename = uniqid('up_', true) . ($safeExt ? ".{$safeExt}" : '');
        $path = $targetDir . '/' . $filename;

        if (!$file->saveAs($path)) {
            return $this->asJson(['error' => 'Failed to save uploaded file. Check PHP temp dir permissions and limits.']);
        }

        // Persist metadata
        $m = new File();
        $m->tenant_id    = $tenantId;
        $m->original_name = $file->name;
        $m->path         = $path;
        $m->mime_type    = $file->type;
        $m->size         = $file->size;
        $m->created_by   = Yii::$app->user->id ?? null;
        $m->created_at   = $m->updated_at = time();

        if (!$m->save(false)) {
            // Rollback file if DB save fails
            @unlink($path);
            return $this->asJson(['error' => 'Failed to save file record.']);
        }

        // Success
        if ($request->isAjax) {
            return $this->asJson([
                'id'   => $m->id,
                'name' => $m->original_name,
                'url'  => \yii\helpers\Url::to(['/file/serve', 'id' => $m->id], true),
            ]);
        }

        Yii::$app->session->setFlash('success', "Uploaded: {$m->original_name}");
        return $this->redirect(['index']);
    }


    public function actionServe($id)
    {
        $m = File::findOne($id);
        if (!$m) throw new \yii\web\NotFoundHttpException();

        // optional: permission check
        // if (!Yii::$app->user->can('file.view')) throw new \yii\web\ForbiddenHttpException();

        if (!is_file($m->path)) throw new \yii\web\NotFoundHttpException('File missing on disk.');

        return Yii::$app->response->sendFile($m->path, $m->original_name, [
            'mimeType' => $m->mime_type,
            'inline'   => str_starts_with($m->mime_type, 'image/') || $m->mime_type === 'application/pdf',
        ]);
    }
}
