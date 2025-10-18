<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $searchModel \app\models\search\FileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Files';
?>

<div class="card shadow-sm">
  <div class="card-header"><h5 class="mb-0">Files</h5></div>
  <div class="card-body">

    <form method="get" class="row g-2 align-items-end mb-3">
      <div class="col-md-6">
        <label class="form-label">Search</label>
        <input type="text" name="FileSearch[q]" value="<?= Html::encode($searchModel->q) ?>" class="form-control" placeholder="Search name, type, or path">
      </div>
      <div class="col-md-2">
        <label class="form-label">Rows / page</label>
        <select name="FileSearch[perPage]" class="form-select">
          <?php foreach ([10,20,50,100] as $n): ?>
            <option value="<?= $n ?>" <?= (int)$searchModel->perPage===$n?'selected':''; ?>><?= $n ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-4 text-md-end">
        <a href="<?= Url::to(['index']) ?>" class="btn btn-outline-secondary">Reset</a>
        <button class="btn btn-primary">Apply</button>
        <?= Html::a('Upload',['upload'],['class'=>'btn btn-success']) ?>
      </div>
    </form>

    <?= GridView::widget([
      'dataProvider' => $dataProvider,
      'tableOptions' => ['class'=>'table table-striped align-middle'],
      'headerRowOptions' => ['class'=>'table-light'],
      'columns' => [
        ['attribute'=>'id','contentOptions'=>['style'=>'width:80px']],
        [
          'attribute'=>'original_name',
          'format'=>'raw',
          'value'=>fn($m)=>Html::a(Html::encode($m->original_name), ['serve','id'=>$m->id], ['class'=>'fw-semibold']),
          'contentOptions'=>['style'=>'max-width:360px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;']
        ],
        'mime_type',
        [
          'attribute'=>'size',
          'value'=>fn($m)=>number_format((float)$m->size).' bytes',
          'contentOptions'=>['style'=>'white-space:nowrap'],
        ],
        ['attribute'=>'created_at','format'=>['datetime'],'contentOptions'=>['style'=>'white-space:nowrap;width:180px']],
      ],
      'summary' => '<div class="text-muted">Showing <b>{begin}-{end}</b> of <b>{totalCount}</b> files</div>',
      'pager' => [
        'class'=>'yii\widgets\LinkPager',
        'options' => ['class'=>'pagination mt-3'],
        'linkOptions' => ['class'=>'page-link'],
        'disabledListItemSubTagOptions' => ['class'=>'page-link'],
      ],
    ]); ?>
  </div>
</div>
