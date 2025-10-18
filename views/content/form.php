<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $model \app\models\Content */

$this->title = $model->isNewRecord ? 'Create Article' : 'Update Article';
?>
<h1><?= $this->title ?></h1>

<?php $f = ActiveForm::begin(['id'=>'content-form']); ?>
  <?= $f->field($model,'title') ?>
  <?= $f->field($model,'body')->textarea(['rows'=>10]) ?>
  <?= $f->field($model,'status')->dropDownList(['draft'=>'draft','published'=>'published','archived'=>'archived']) ?>
  <?= Html::submitButton('Save',['class'=>'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>
