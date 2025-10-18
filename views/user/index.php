<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Users';
?>
<div class="card shadow-sm mt-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Users</h5>
        <?= Html::a('Add User', ['create'], ['class' => 'btn btn-success']) ?>
    </div>
    <div class="card-body">

        <?php $form = ActiveForm::begin([
            'method' => 'get',
            'action' => ['index'],
            'options' => ['class' => 'mb-3 d-flex gap-2'],
        ]); ?>
        <?= Html::input('text', 'UserSearch[globalSearch]', $searchModel->globalSearch, [
            'class' => 'form-control',
            'placeholder' => 'Search by username or email...',
            'style' => 'max-width:300px;',
        ]) ?>
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Reset', ['index'], ['class' => 'btn btn-outline-secondary']) ?>
        <?php ActiveForm::end(); ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => ['class' => 'table table-striped align-middle'],
            'headerRowOptions' => ['class' => 'table-light'],
            'columns' => [
                ['attribute' => 'id', 'contentOptions' => ['style' => 'width:60px']],
                'username',
                'email:email',
                [
                    'label' => 'Role',
                    'value' => function ($m) {
                        $roles = Yii::$app->authManager->getRolesByUser($m->id);
                        return $roles ? ucfirst(array_key_first($roles)) : '-';
                    },
                    'contentOptions' => ['style' => 'width:100px; white-space:nowrap;']
                ],
                [
                    'attribute' => 'tenant_id',
                    'contentOptions' => ['style' => 'width:80px'],
                ],
                [
                    'attribute' => 'created_at',
                    'format' => ['datetime'],
                    'contentOptions' => ['style' => 'white-space:nowrap;width:180px'],
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update} {delete}',
                    'contentOptions' => ['style' => 'white-space:nowrap;width:100px'],
                    'buttons' => [
                        'update' => fn($url, $m) => Html::a('Edit', ['update', 'id' => $m->id], ['class' => 'btn btn-sm btn-outline-primary']),
                        'delete' => fn($url, $m) => Html::a('Delete', ['delete', 'id' => $m->id], [
                            'class' => 'btn btn-sm btn-outline-danger',
                            'data' => ['confirm' => 'Delete this user?', 'method' => 'post'],
                        ]),
                    ],
                ],
            ],
            'summary' => '<div class="text-muted">Showing <b>{begin}-{end}</b> of <b>{totalCount}</b> users</div>',
            'pager' => [
                'class' => 'yii\widgets\LinkPager',
                'options' => ['class' => 'pagination mt-3'],
                'linkOptions' => ['class' => 'page-link'],
            ],
        ]); ?>
    </div>
</div>