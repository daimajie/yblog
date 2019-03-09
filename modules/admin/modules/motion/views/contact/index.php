<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '消息列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contact-index box box-primary">
    <div class="box-body table-responsive no-padding">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'email:email',
                'subject',
                'message',
                [
                    'attribute' => 'created_at',
                    'format' => ['date', 'php:Y-m-d']
                ],

                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view} {delete}'
                ],
            ],
        ]); ?>
    </div>
</div>
