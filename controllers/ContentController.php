<?php

namespace app\controllers;

use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use Yii;
use app\models\Content;

class ContentController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    ['allow' => true, 'roles' => ['@']],
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        $searchModel  = new \app\models\search\ContentSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render('index', compact('searchModel', 'dataProvider'));
    }

    public function actionCreate()
    {
        if (!Yii::$app->user->can('content.create')) throw new \yii\web\ForbiddenHttpException();

        $model = new Content();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }
        return $this->render('form', ['model' => $model]);
    }

    public function actionView($id)
    {
        $model = Content::findOne($id);
        if (!$model) {
            throw new \yii\web\NotFoundHttpException('Article not found.');
        }
        return $this->render('view', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        if (!Yii::$app->user->can('content.update')) throw new \yii\web\ForbiddenHttpException();

        $model = Content::findOne($id);
        if (!$model) throw new \yii\web\NotFoundHttpException();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }
        return $this->render('form', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        if (!Yii::$app->user->can('content.delete')) throw new \yii\web\ForbiddenHttpException();

        $m = Content::findOne($id);
        if ($m) $m->delete();
        return $this->redirect(['index']);
    }
}
