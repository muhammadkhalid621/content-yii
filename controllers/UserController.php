<?php

namespace app\controllers;

use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use Yii;
use app\models\User;
use app\models\search\UserSearch;

class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // must be logged in
                    ],
                ],
            ],
        ];
    }

    /** 
     * List all users with search and filters
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', compact('searchModel', 'dataProvider'));
    }

    /**
     * Create a new user
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post())) {
            if (!empty($model->password_hash)) {
                $model->setPassword($model->password_hash);
            }
            $model->auth_key   = Yii::$app->security->generateRandomString();
            $model->tenant_id  = Yii::$app->tenant->id ?? 1;
            $model->created_at = $model->updated_at = time();

            if ($model->save(false)) {
                $role = Yii::$app->request->post('role');
                $this->assignRole($model->id, $role);
                Yii::$app->session->setFlash('success', 'User created successfully.');
                return $this->redirect(['index']);
            }
        }

        return $this->render('form', ['model' => $model]);
    }

    /**
     * Update an existing user
     */
    public function actionUpdate($id)
    {
        $model = User::findOne($id);
        if (!$model) throw new NotFoundHttpException('User not found.');

        if ($model->load(Yii::$app->request->post())) {
            if (!empty($model->password_hash)) {
                $model->setPassword($model->password_hash);
            }
            $role = Yii::$app->request->post('role');
            $this->assignRole($model->id, $role);
            $model->updated_at = time();

            if ($model->save(false)) {
                Yii::$app->session->setFlash('success', 'User updated successfully.');
                return $this->redirect(['index']);
            }
        }

        return $this->render('form', ['model' => $model]);
    }

    /**
     * Delete a user
     */
    public function actionDelete($id)
    {
        $model = User::findOne($id);
        if ($model) {
            $model->delete();
            Yii::$app->session->setFlash('success', 'User deleted successfully.');
        }

        return $this->redirect(['index']);
    }

    private function assignRole($userId, $roleName)
    {
        if (!$roleName) return;
        $am = Yii::$app->authManager;
        $am->revokeAll($userId);
        $role = $am->getRole($roleName);
        if ($role) $am->assign($role, $userId);
    }
}
