<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Yii2 CMS Prototype';
?>

<div class="site-index">
    <h1>Yii2 CMS Prototype</h1>

    <?php if (Yii::$app->user->isGuest): ?>
        <p class="lead mt-4">
            Welcome to the CMS prototype.  
            Please
            <?= Html::a('log in', ['/site/login'], ['class' => 'btn btn-primary']) ?>
            to continue.
        </p>
    <?php else: ?>
        <p class="lead mt-4">
            Hi, <strong><?= Html::encode(Yii::$app->user->identity->username) ?></strong>!  
            Use the top menu or these quick links:
        </p>

        <p>
            <?= Html::a('Articles', ['/content/index'], ['class' => 'btn btn-outline-primary me-2']) ?>
            <?= Html::a('Files', ['/file/index'], ['class' => 'btn btn-outline-secondary me-2']) ?>
            <?= Html::a('Audit Logs', ['/audit/index'], ['class' => 'btn btn-outline-info']) ?>
        </p>
    <?php endif; ?>
</div>
