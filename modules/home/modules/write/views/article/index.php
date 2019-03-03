<?php
use app\models\content\Topic;
use yii\grid\GridView;
use yii\helpers\Html;
use app\widgets\Alert;
use yii\helpers\Url;
use app\components\ViewHelper;
use app\models\content\Article;

$params = Yii::$app->request->queryParams;
?>
<!-- Content -->
<div class="row">

    <!-- post content -->
    <div class="col-lg-12 blog__content mb-30">

        <div class="title-wrap">
            <h3>写作中心 - <small><?= Html::encode($model->name)?></small></h3>
        </div>
        <div class="row">
            <div class="col-lg-12 blog__content mb-30 sidebar--right">
                <div class="row mb-3">
                    <div class="col-lg-6">
                        <?= Html::a('<span>话题列表</span>', ['topic/index'], ['class' => 'btn btn-lg btn-color']) ?>
                        <?= Html::a('<span>创建文章</span>', ['create','id'=>$model->id], ['class' => 'btn btn-lg']) ?>
                    </div>
                    <div class="col-lg-6">
                        <?= Html::beginForm(['','id'=>$model->id], 'get') ?>
                        <div class="row">
                            <div class="col-md-3">
                                <?= Html::dropDownList('check', isset($params['check'])?$params['check']:'',[
                                    Article::CHECK_WAIT => '待审核',
                                    Article::CHECK_ADOPT => '通过',
                                    Article::CHECK_DENIAL => '失败',
                                ], [
                                    'prompt'=>'审核状态',
                                ]) ?>
                            </div>
                            <div class="col-md-3">
                                <?= Html::dropDownList('status', isset($params['status'])?$params['status']:'',[
                                    Article::STATUS_NORMAL => '公示文章',
                                    Article::STATUS_DRAFT  => '草稿箱',
                                ], [
                                    'prompt'=>'文章状态',
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <div class="search-form">
                                    <?= Html::input('text', 'title', isset($params['title'])?trim($params['title']):'', [
                                        'placeholder'=>'文章标题',
                                        'autocomplete'=>"off"
                                    ]) .
                                    Html::submitButton('<i class="ui-search search-icon"></i>', [
                                        'class' => 'search-button btn btn-lg btn-color btn-button'
                                    ])
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?= Html::endForm() ?>
                    </div>
                </div>
                <div class="row"><?= Alert::widget(['options' => ['class'=>'col-lg-12']]) ?></div>
                <!-- content -->
                <?= GridView::widget([
                    'options' => [
                        'class' => '',
                    ],
                    'headerRowOptions' => [
                        'class' => 'thead-light'
                    ],
                    'tableOptions' => [
                        'class' => 'table'
                    ],
                    'id' => 'grid',
                    'dataProvider' => $dataProvider,
                    'layout' => "{items}\n{summary}\n{pager}",
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'attribute' => 'title',
                            'value' => function($model){
                                return $model->title;
                            },
                            'contentOptions' => [
                                'width' => 420,
                            ],
                        ],
                        [
                            'attribute' => 'check',
                            'value' => function($model){
                                $tmp = [
                                    Article::CHECK_WAIT => '待审核',
                                    Article::CHECK_ADOPT => '通过',
                                    Article::CHECK_DENIAL => '失败',
                                ];

                                return $tmp[$model->check];
                            },
                        ],
                        [
                            'attribute' => 'status',
                            'value' => function($model){
                                $tmp = [
                                    Article::STATUS_NORMAL => '公示',
                                    Article::STATUS_DRAFT => '草稿',
                                ];
                                return $tmp[$model->status];
                            }
                        ],
                        [
                            'attribute' => 'visited',
                        ],
                        [
                            'attribute' => 'comment',
                        ],
                        [
                            'attribute' => 'topic_id',
                            'value' => function($model){
                                return $model->topic->name;
                            },
                        ],
                        [
                            'attribute' => 'created_at',
                            'format' => ['date', 'php:Y-m-d'],
                        ],

                        [
                            'class' => 'yii\grid\ActionColumn',
                            'header' => '<a href="javascript:;">操作</a>',
                            'template' => '{view} {update} {delete}',
                            'buttons'=>[
                                'view' => function ($url, $model, $key) {
                                    return Html::a('<small>查看</small>', $url);
                                },

                                'update' => function ($url, $model, $key) {
                                    return Html::a('<small>修改</small>', $url);
                                },

                                'delete' => function ($url, $model, $key) {
                                    return Html::a('<small>删除</small>', $url, [
                                        'data' => [
                                            'confirm' => '您确定要删除该话题吗?',
                                            'method' => 'post',
                                        ],
                                    ]);
                                },

                            ],
                        ],

                    ],

                ]); ?>
                <!-- \content -->
            </div>
        </div>
    </div>
    <!-- end col -->
</div>
<!-- end content -->
