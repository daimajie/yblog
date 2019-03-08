<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\rbac\AuthItem;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '权限列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-index box box-primary">
    <div class="box-header with-border">

        <div class="pull-left">
            <?= Html::a('创建权限', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
        </div>
        <div class="pull-right">
            <?php echo $this->render('_search', [
                'model' => $searchModel,
            ]); ?>
        </div>

    </div>
    <div class="box-body table-responsive no-padding">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'name',
                [
                    'attribute' => 'type',
                    'value' => function($model){
                        $tmp = [
                            AuthItem::TYPE_ROLE => '角色',
                            AuthItem::TYPE_ROUTER => '路由'
                        ];
                        return $tmp[$model->type];
                    }
                ],
                'description:ntext',
                'rule_name',
                [
                    'attribute' => 'created_at',
                    'format' => ['date', 'php:Y-m-d']
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update} {delete} {view}',
                    'buttons' => [

                    ],
                    'visibleButtons' => [
                        'view' => function($model){
                            return $model->type == AuthItem::TYPE_ROLE;
                        },
                    ]
                ],
            ],
        ]); ?>
    </div>
</div>
