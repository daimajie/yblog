<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\member\SearchUser */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '日志列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-form-index box box-primary">
    <div class="box-header with-border">
        <div class="pull-left">
            <?= Html::a('清空日志', ['flush'], [
                'class' => 'btn btn-success btn-flat',
                'onclick' => 'javascript:return confirm("您确定删除所有日志信息吗？");'
            ]) ?>
        </div>
        <div class="pull-right">
        </div>
    </div>
    <div class="box-body table-responsive no-padding">
        <?= GridView::widget([
            'options' => [
                'tag' => false,
            ],
            'tableOptions' => [
                'class' => 'ui grey table celled',
            ],
            'pager' => [
                'options'=>['class'=>'ui pagination menu tiny','style'=>'list-style:none'],
                'linkOptions' => ['tag'=>'a', 'class' => 'item'],
            ],
            'dataProvider' => $dataProvider,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                'id',
                'level',
                'category',
                'log_time:datetime',
                'prefix',
                //'message',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'options' => ['width'=>200],
                    'header' => '<a href="javascript:;">操作</a>',
                    'template' => '{view}{delete}',
                ],
            ],
        ]); ?>
    </div>
</div>




