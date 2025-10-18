<?php

namespace app\controllers;

use yii\web\Controller;
use yii\data\ActiveDataProvider;
use Yii;
use app\models\AuditLog;

class AuditController extends Controller
{
    public function actionIndex()
    {
        $searchModel  = new \app\models\search\AuditLogSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render('index', compact('searchModel', 'dataProvider'));
    }
}
