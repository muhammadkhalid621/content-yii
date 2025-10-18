<?php
use yii\widgets\ActiveForm; use yii\helpers\Html;
$this->title = 'Forgot Password';
?>
<h1>Forgot Password</h1>
<?php $f = ActiveForm::begin(); ?>
  <?= $f->field($model,'email')->input('email') ?>
  <?= Html::submitButton('Send reset link',['class'=>'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>
