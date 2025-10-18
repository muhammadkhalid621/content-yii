<?php
/** @var $model \app\models\Content */
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = Html::encode($model->title);
$this->params['breadcrumbs'][] = ['label' => 'Articles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-view">
    <h1><?= Html::encode($model->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => ['confirm' => 'Are you sure?', 'method' => 'post'],
        ]) ?>
        <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'slug',
            'status',
            [
                'attribute' => 'created_at',
                'format' => ['datetime'],
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['datetime'],
            ],
        ],
    ]) ?>

    <hr>
    <div class="mt-3">
        <h4>Body</h4>
        <!-- body is rich text; render as HTML (it comes from trusted editors in this prototype) -->
        <div><?= $model->body ?></div>
    </div>
</div>
