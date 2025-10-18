<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = $model->isNewRecord ? 'Add User' : 'Edit User';
$roles = ['admin' => 'Admin', 'editor' => 'Editor', 'viewer' => 'Viewer'];

// Get existing user role
$currentRole = '';
if (!$model->isNewRecord) {
    $assigned = Yii::$app->authManager->getRolesByUser($model->id);
    $currentRole = $assigned ? array_key_first($assigned) : '';
}
?>
<div class="card shadow-sm mt-3">
  <div class="card-header">
    <h5 class="mb-0"><?= Html::encode($this->title) ?></h5>
  </div>
  <div class="card-body">

    <?php $f = ActiveForm::begin(); ?>
      <?= $f->field($model, 'username')->textInput(['maxlength' => true]) ?>
      <?= $f->field($model, 'email')->input('email') ?>
      <?= $f->field($model, 'password_hash')->passwordInput()->label('Password (leave blank to keep)') ?>

      <div class="mb-3">
        <label class="form-label">Role</label>
        <?= Html::dropDownList('role', $currentRole, $roles, ['class' => 'form-select', 'prompt' => 'Select role']) ?>
      </div>

      <div class="mt-3">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-secondary ms-2']) ?>
      </div>
    <?php ActiveForm::end(); ?>

  </div>
</div>
