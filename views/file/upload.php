<?php $this->title = 'Upload'; ?>
<h1>Upload</h1>

<form action="" method="post" enctype="multipart/form-data">
  <?= \yii\helpers\Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->getCsrfToken()); ?>
  <div class="mb-3">
    <input type="file" name="upload" class="form-control" required>
  </div>
  <button class="btn btn-primary">Upload</button>
</form>
