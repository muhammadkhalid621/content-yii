<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $model \app\models\forms\LoginForm */

$this->title = 'Login';
?>
<h1>Login</h1>

<?php $f = ActiveForm::begin([
  'id' => 'login-form',
  'method' => 'post',
]); ?>
<?= $f->field($model, 'username') ?>
<?= $f->field($model, 'password')->passwordInput() ?>
<?= $f->field($model, 'rememberMe')->checkbox() ?>
<?= Html::submitButton('Login', ['class' => 'btn btn-primary']) ?>
<p class="mt-2">
  <a href="/site/request-password-reset">Forgot password?</a>
</p>
<?php ActiveForm::end(); ?>