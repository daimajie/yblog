<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\motion\Contact */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => '消息列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contact-view box box-primary">
    <div class="box-header">
        <?= Html::a('消息列表', ['index'], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('删除消息', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger btn-flat',
            'data' => [
                'confirm' => '您确定要删除该消息吗?',
                'method' => 'post',
            ],
        ]) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'email:email',
                'subject',
                'message',
                [
                    'attribute' => 'user_id',
                    'value' => function($model){
                        return $model->user->username;
                    }
                ],
                'created_at:datetime',
            ],
        ]) ?>
    </div>
</div>
