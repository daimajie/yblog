<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\admin\models\Article;
use app\components\Helper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\SearchArticle */
/* @var $dataProvider yii\data\ActiveDataProvider */

//当前展示状态(1公示文章 2草稿 3回收站)
$status = (int) $searchModel->status;

$this->title = '文章列表';
$this->params['breadcrumbs'][] = $this->title;


//定义下拉菜单
$dropBtn = <<<DROP
<div class="btn-group dropup">
  <button type="button" class="btn btn-info btn-flat">批量操作</button>
  <button type="button" class="btn btn-info btn-flat dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <span class="caret"></span>
    <span class="sr-only">Toggle Dropdown</span>
  </button>
  <ul class="dropdown-menu">
    <li><a href="#">批量删除</a></li>
    <li><a href="#">批量恢复</a></li>
    <li><a href="#">批量审核</a></li>
  </ul>
</div>
DROP;



?>
<div class="article-index box box-primary">
    <div class="box-header with-border">
        <div class="pull-left">
            <?= Html::a('创建文章', ['create'], ['class' => 'btn btn-success btn-flat']) ?>

            <div class="btn-group" role="group" aria-label="...">
                <?php
                $normal = ($status != 2 && $status != 3) ? 'active' : '';
                echo Html::a('公示文章', Url::current(['status'=>Article::STATUS_NORMAL]), [
                        'class' => 'btn btn-primary btn-flat ' . $normal
                ])
                ?>
                <?php
                $draft = ($status == 2) ? 'active' : '';
                echo Html::a('草稿箱', Url::current(['status'=>Article::STATUS_DRAFT]), [
                        'class' => 'btn btn-danger btn-flat ' . $draft
                ]) ?>
                <?php
                $recycle = ($status == 3) ? 'active' : '';
                echo Html::a('回收站', Url::current(['status'=>Article::STATUS_RECYCLE]), [
                        'class' => 'btn btn-warning btn-flat ' . $recycle
                ]) ?>
            </div>
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
                [
                    'class'=> 'yii\grid\CheckboxColumn',
                    'headerOptions' => ['id'=>'select-all'],
                    'name' => 'article_ids[]',
                    'footerOptions' => ['colspan' => 12],
                    'footer' => $dropBtn
                ],
                [
                    'class' => 'yii\grid\SerialColumn',
                    'footerOptions' => [
                            'hidden' => true
                        ],
                ],
                [
                    'attribute' => 'id',
                    'footerOptions' => [
                        'hidden' => true
                    ],
                ],
                [
                    'attribute' => 'title',
                    'value' => function($model){
                        return Helper::truncate_utf8_string($model->title, 28);
                    },
                    'contentOptions' => [
                        'width' => 485,
                    ],
                    'footerOptions' => [
                        'hidden' => true
                    ],
                ],
                /*[
                    'attribute' => 'status',
                    'value' => function($model){
                        $tmp = [
                            Article::STATUS_NORMAL => '公示状态',
                            Article::STATUS_DRAFT => '草稿箱',
                            Article::STATUS_RECYCLE => '回收站',
                        ];
                        return $tmp[$model->status];
                    }
                ],*/
                [
                    'attribute' => 'check',
                    'value' => function($model){
                        $tmp = [
                            ARTICLE::CHECK_WAIT => '待审核',
                            ARTICLE::CHECK_ADOPT => '审核通过',
                            ARTICLE::CHECK_DENIAL => '审核失败',
                        ];

                        return $tmp[$model->check];
                    },
                    'footerOptions' => [
                        'hidden' => true
                    ],
                ],
                [
                    'attribute' => 'visited',
                    'footerOptions' => [
                        'hidden' => true
                    ],
                ],
                [
                    'attribute' => 'comment',
                    'footerOptions' => [
                        'hidden' => true
                    ],
                ],
                [
                    'attribute' => 'topic_id',
                    'value' => function($model){
                        return $model->topic->name;
                    },
                    'footerOptions' => [
                        'hidden' => true
                    ],
                ],
                [
                    'attribute' => 'user_id',
                    'footerOptions' => [
                        'hidden' => true
                    ],
                ],
                [
                    'attribute' => 'created_at',
                    'format' => ['date', 'php:Y-m-d'],
                    'footerOptions' => [
                        'hidden' => true
                    ],
                ],
                [
                    'attribute' => 'updated_at',
                    'format' => ['date', 'php:Y-m-d'],
                    'footerOptions' => [
                        'hidden' => true
                    ],
                ],

                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => '<a href="javascript:;">操作</a>',
                    'template' => '{view} {update} {delete} {restore} {discard}',
                    'buttons'=>[

                        'restore' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-repeat" aria-hidden="true"></span>', $url, [
                                'class'=>'ui green basic button del-btn',
                                'title' => '恢复',
                                'data' => [
                                    'confirm' => '您要恢复该文章为发布文章吗?',
                                ],
                            ]);
                        },
                        'discard' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>', $url, [
                                'class'=>'ui blue basic button',
                                'title' => '彻底删除',
                                'data' => [
                                    'confirm' => '您确定要彻底删除该文章吗?',
                                    'method' => 'post',
                                ],
                            ]);
                        },

                    ],
                    'visibleButtons' => [
                        'update'  => $status === Article::STATUS_RECYCLE ? false : true,
                        'delete'  => $status === Article::STATUS_RECYCLE ? false : true,
                        'restore' => $status === Article::STATUS_RECYCLE ? true : false,
                        'discard' => $status === Article::STATUS_RECYCLE ? true : false,
                    ],
                    'footerOptions' => [
                        'hidden' => true
                    ],
                ],



            ],

            'showFooter' => true,


        ]); ?>
    </div>
</div>

