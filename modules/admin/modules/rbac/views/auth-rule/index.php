<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '规则列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-rule-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('创建规则', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'name',
                'data',
                [
                    'attribute' => 'created_at',
                    'format' => ['date', 'php:Y-m-d']
                ],
                [
                    'attribute' => 'updated_at',
                    'format' => ['date', 'php:Y-m-d']
                ],

                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{delete}',
                ],
            ],
        ]); ?>
    </div>
</div>
