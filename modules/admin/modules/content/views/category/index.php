<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\content\SearchCategory */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '创建分类';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index box box-primary">
    <div class="box-header with-border">
        <div class="pull-left">
            <?= Html::a('创建分类', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
        </div>
        <div class="pull-right">
            <?php echo $this->render('_search', ['model' => $searchModel]); ?>
        </div>
    </div>
    <div class="box-body table-responsive no-padding">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'name',
                'user_id',
                [
                    'attribute' => 'created_at',
                    'format' => ['date', 'php:Y-m-d']
                ],
                [
                    'attribute' => 'created_at',
                    'format' => ['date', 'php:Y-m-d']
                ],

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
