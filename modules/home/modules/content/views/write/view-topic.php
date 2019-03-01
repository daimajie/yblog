<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/2/28
 * Time : 11:57
 */
use app\models\content\Topic;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\Helper;
use app\models\content\Article;

$params = Yii::$app->request->queryParams;
?>
<!-- Content -->
<div class="row">

    <!-- post content -->
    <div class="col-lg-12 blog__content mb-30">

        <div class="title-wrap">
            <h3>写作中心</h3>
        </div>
        <div class="row">
            <div class="col-lg-12 blog__content mb-30 sidebar--right">
                <div class="row">
                    <div class="col-lg-6">
                        <?= Html::a('<span>创建文章</span>', ['create_topic'], ['class' => 'btn btn-lg btn-color']) ?>
                    </div>
                    <div class="col-lg-6">
                        <?= Html::beginForm(['index'], 'get') ?>
                        <div class="row">

                        </div>
                        <?= Html::endForm() ?>
                    </div>
                </div>
                <!-- content -->
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'layout' => "{items}\n{summary}\n{pager}",
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'attribute' => 'title',
                            'value' => function($model){
                                return Helper::truncate_utf8_string($model->title, 28);
                            },
                            'contentOptions' => [
                                'width' => 485,
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
                        ['attribute' => 'visited'],
                        ['attribute' => 'comment'],
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
                            'template' => '{view-topic} {update-topic} {delete-topic}',
                            'buttons'=>[
                                'view-article' => function ($url, $model, $key) {
                                    return Html::a('<small>查看</small>', $url);
                                },

                                'update-article' => function ($url, $model, $key) {
                                    return Html::a('<small>修改</small>', $url);
                                },

                                'delete-article' => function ($url, $model, $key) {
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
