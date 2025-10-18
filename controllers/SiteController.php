<?php

namespace app\controllers;

use yii\web\Controller;
use Yii;
use app\models\forms\LoginForm;

class SiteController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        // If already logged in, just go home with a flash
        if (!Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('info', 'You are already logged in.');
            return $this->goHome();
        }

        $model = new LoginForm();

        // If user tried to access a protected page, remember it
        if (!Yii::$app->request->isPost && !Yii::$app->user->getReturnUrl()) {
            Yii::$app->user->setReturnUrl(Yii::$app->homeUrl); // avoid loops
        }

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            // align tenant context with the logged-in user
            Yii::$app->session->set('tenant_id', Yii::$app->user->identity->tenant_id);

            Yii::$app->session->setFlash('success', 'Login successful! Welcome back.');
            return $this->goHome();
        }

        // Show validation errors if any
        return $this->render('login', ['model' => $model]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionError()
    {
        return $this->render('error');
    }
}
