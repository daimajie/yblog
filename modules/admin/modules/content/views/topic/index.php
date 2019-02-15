<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\content\Topic;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\content\SearchTopic */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '话题列表';
$this->params['breadcrumbs'][] = $this->title;

//展示状态
$status = (int)(isset($searchModel->status) ? $searchModel->status : 0);

?>
<div class="topic-index box box-primary">
    <div class="box-header with-border">
        <div class="pull-left">
            <?= Html::a('创建话题', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
            <?php
            echo Html::a('回收站', Url::current(['status'=>Topic::STATUS_RECYCLE]), [
                'class' => 'btn btn-warning btn-flat '
            ]) ?>
        </div>
        <div class="pull-right">
            <?php echo $this->render('_search', [
                'model' => $searchModel,
                'category_items' => $category_items
            ]); ?>
        </div>
    </div>
    <div class="box-body table-responsive no-padding">

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'name',
                'count',
                [
                    'attribute' => 'status',
                    'enableSorting' => false,
                    'value' => function($model){
                        $tmp = [
                            Topic::STATUS_NORMAL => '正常',
                            Topic::STATUS_FINISH => '完结',
                            Topic::STATUS_RECYCLE => '回收站'
                        ];
                        return $tmp[$model->status];
                    }
                ],
                [
                    'label' => '所属分类',
                    'value' => function($model){
                        if($model->category_id){

                            return '《 ' . $model->category->name . ' 》';
                        }
                        return '';
                    }
                ],
                [
                    'attribute' => 'check',
                    'enableSorting' => false,
                    'value' => function($model){
                        $tmp = ['待审核','审核通过','审核失败'];
                        return $tmp[$model->check - 1];
                    }
                ],
                [
                    'attribute' => 'secrecy',
                    'enableSorting' => false,
                    'value' => function($model){
                        $tmp = ['私密话题','公开话题'];
                        return $tmp[$model->secrecy - 1];
                    }
                ],
                [
                    'attribute' => 'user_id',
                    'enableSorting' => false,
                ],
                [
                    'attribute' => 'created_at',
                    'format' => ['date', 'php:Y-m-d']
                ],
                [
                    'attribute' => 'created_at',
                    'format' => ['date', 'php:Y-m-d']
                ],

                //['class' => 'yii\grid\ActionColumn'],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => '<a href="javascript:;">操作</a>',
                    'template' => '{view} {update} {delete} {discard}',
                    'buttons'=>[

                        'discard' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>', $url, [
                                'class'=>'ui blue basic button',
                                'title' => '彻底删除',
                                'data' => [
                                    'confirm' => '您确定要彻底删除该话题吗?',
                                    'method' => 'post',
                                ],
                            ]);
                        },

                    ],
                    'visibleButtons' => [
                        'delete'  => $status === Topic::STATUS_RECYCLE ? false : true,
                        'discard' => $status === Topic::STATUS_RECYCLE ? true : false,
                    ],
                ],
            ],
        ]); ?>
    </div>
</div>
