<?php
use app\models\content\Topic;
use yii\grid\GridView;
use yii\helpers\Html;
use app\widgets\Alert;
use yii\helpers\Url;

$params = Yii::$app->request->queryParams;
?>
<!-- Content -->
<div class="row">

    <!-- post content -->
    <div class="col-lg-12 blog__content mb-30">

        <div class="title-wrap">
            <h3>写作中心 - <small>话题列表</small></h3>
        </div>
        <div class="row">
            <div class="col-lg-12 blog__content mb-30 sidebar--right">
                <div class="row">
                    <div class="col-lg-6">
                        <?= Html::a('<span>创建话题</span>', ['create'], ['class' => 'btn btn-lg btn-color']) ?>
                    </div>
                    <div class="col-lg-6">
                        <?= Html::beginForm(['index'], 'get') ?>
                        <div class="row">
                            <div class="col-md-3">
                                <?= Html::dropDownList('secrecy', isset($params['secrecy'])?$params['secrecy']:'',[
                                    '1' => '私有',
                                    '2' => '公开',
                                ], [
                                    'prompt'=>'选择状态',
                                ]) ?>
                            </div>
                            <div class="col-md-3">
                                <?= Html::dropDownList('category_id', isset($params['category_id'])?$params['category_id']:'', $category_items, [
                                    'prompt'=>'选择分类'
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <div class="search-form">
                                    <?= Html::input('text', 'name', isset($params['name'])?trim($params['name']):'', [
                                        'placeholder'=>'话题名称',
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
                    'dataProvider' => $dataProvider,
                    'layout' => "{items}\n{summary}\n{pager}",
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'name',
                        'count',
                        [
                            'attribute' => 'status',
                            'enableSorting' => false,
                            'value' => function($model){
                                $tmp = [
                                    Topic::STATUS_NORMAL => '连载',
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
                                    return $model->category->name;
                                }
                                return '';
                            }
                        ],
                        [
                            'attribute' => 'check',
                            'enableSorting' => false,
                            'value' => function($model){
                                if($model->secrecy == Topic::SECR_PRIVATE)
                                    return '';

                                $tmp = ['待审核','通过','失败'];
                                return $tmp[$model->check - 1];
                            }
                        ],
                        [
                            'attribute' => 'secrecy',
                            'enableSorting' => false,
                            'value' => function($model){
                                $tmp = ['私密','公开'];
                                return $tmp[$model->secrecy - 1];
                            }
                        ],
                        [
                            'attribute' => 'created_at',
                            'format' => ['date', 'php:Y-m-d']
                        ],

                        [
                            'class' => 'yii\grid\ActionColumn',
                            'header' => '<a href="javascript:;">操作</a>',
                            'template' => '{view} {update} {delete} {article-list}',
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
                                'article-list' => function($url, $model, $key){
                                    return Html::a('<small>文章列表</small>', Url::to(['article/index','id'=>$model->id]));
                                }

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
