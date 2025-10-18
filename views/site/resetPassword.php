<?php
use yii\widgets\ActiveForm; use yii\helpers\Html;
$this->title = 'Reset Password';
?>
<h1>Reset Password</h1>
<?php $f = ActiveForm::begin(); ?>
  <?= $f->field($model,'password')->passwordInput() ?>
  <?= Html::submitButton('Change password',['class'=>'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>
