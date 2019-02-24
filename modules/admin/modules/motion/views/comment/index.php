<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\components\ViewHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\motion\SearchComment */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '评论列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comment-index box box-primary">
    <div class="box-header with-border">
        <div class="pull-left">
            <?php //Html::a('Create Comment', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
        </div>
        <div class="pull-right">
            <?php echo $this->render('_search', ['model' => $searchModel]); ?>
        </div>
    </div>
    <div class="box-body table-responsive no-padding">

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',

                [
                        'attribute' => 'content',
                    'value' => function($model){
                        return ViewHelper::truncate_utf8_string($model->content, 56);
                    }
                ],
                'article_id',
                [
                    'attribute' => 'user_id',
                    'value' => function($model){
                        return ViewHelper::username($model->user->username, $model->user->nickname);
                    }
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
