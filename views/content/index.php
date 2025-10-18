<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $searchModel \app\models\search\ContentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Articles';
?>

<div class="card shadow-sm">
  <div class="card-header">
    <h5 class="mb-0">Articles</h5>
  </div>

  <div class="card-body">

    <!-- Top toolbar -->
    <form method="get" class="row g-2 align-items-end mb-3">
      <div class="col-md-4">
        <label class="form-label">Search</label>
        <input type="text" name="ContentSearch[q]" value="<?= Html::encode($searchModel->q) ?>" class="form-control" placeholder="Search title, slug, body">
      </div>
      <div class="col-md-3">
        <label class="form-label">Status</label>
        <select name="ContentSearch[status]" class="form-select">
          <option value="">All</option>
          <?php foreach (['draft','published','archived'] as $s): ?>
            <option value="<?= $s ?>" <?= $searchModel->status===$s?'selected':''; ?>><?= ucfirst($s) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label">Rows / page</label>
        <select name="ContentSearch[perPage]" class="form-select">
          <?php foreach ([10,20,50,100] as $n): ?>
            <option value="<?= $n ?>" <?= (int)$searchModel->perPage===$n?'selected':''; ?>><?= $n ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-3 text-md-end">
        <a href="<?= Url::to(['index']) ?>" class="btn btn-outline-secondary">Reset</a>
        <button class="btn btn-primary">Apply</button>
        <?= Html::a('Create Article',['create'],['class'=>'btn btn-success']) ?>
      </div>
    </form>

    <?= GridView::widget([
      'dataProvider' => $dataProvider,
      'tableOptions' => ['class'=>'table table-striped align-middle'],
      'headerRowOptions' => ['class'=>'table-light'],
      'columns' => [
        ['attribute'=>'id','contentOptions'=>['style'=>'width:80px']],
        [
          'attribute'=>'title',
          'format'=>'raw',
          'value'=>fn($m)=>Html::a(Html::encode($m->title), ['view','id'=>$m->id], ['class'=>'fw-semibold']),
        ],
        'slug',
        [
          'attribute'=>'status',
          'format'=>'raw',
          'value'=>function($m){
            switch ($m->status) {
              case 'published':
                $class = 'bg-success';
                break;
              case 'draft':
                $class = 'bg-secondary';
                break;
              case 'archived':
                $class = 'bg-dark';
                break;
              default:
                $class = 'bg-secondary';
            }
            return '<span class="badge '.$class.'">'.$m->status.'</span>';
          },
          'contentOptions'=>['style'=>'white-space:nowrap'],
        ],
        ['attribute'=>'updated_at','format'=>['datetime'],'contentOptions'=>['style'=>'white-space:nowrap;width:180px']],
        [
          'class'=>'yii\grid\ActionColumn',
          'template'=>'{view} {update} {delete}',
          'contentOptions'=>['style'=>'white-space:nowrap;width:140px'],
          'buttons'=>[
            'view'=>fn($url,$m)=>Html::a('View',['view','id'=>$m->id],['class'=>'btn btn-sm btn-outline-secondary']),
            'update'=>fn($url,$m)=>Html::a('Edit',['update','id'=>$m->id],['class'=>'btn btn-sm btn-outline-primary']),
            'delete'=>fn($url,$m)=>Html::a('Delete',['delete','id'=>$m->id],[
              'class'=>'btn btn-sm btn-outline-danger',
              'data'=>['confirm'=>'Delete this article?','method'=>'post']
            ]),
          ]
        ],
      ],
      'summary' => '<div class="text-muted">Showing <b>{begin}-{end}</b> of <b>{totalCount}</b> items</div>',
      'pager' => [
        'class' => 'yii\widgets\LinkPager',
        'options' => ['class'=>'pagination mt-3'],
        'linkOptions' => ['class'=>'page-link'],
        'disabledListItemSubTagOptions' => ['class'=>'page-link'],
      ],
    ]); ?>
  </div>
</div>
