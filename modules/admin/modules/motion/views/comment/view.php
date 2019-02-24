<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\motion\Comment */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => '评论列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comment-view box box-primary">
    <div class="box-header">
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger btn-flat',
            'data' => [
                'confirm' => '您确定要删除该评论吗?',
                'method' => 'post',
            ],
        ]) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                [
                        'attribute' => 'user_id',
                    'value' => function($model){
                        return $model->user->username;
                    }
                ],
                [
                    'attribute' => 'created_at',
                    'format' => ['date', 'php:Y-m-d']
                ],
                [
                    'attribute' => 'parent_id',
                    'value' => function($model){

                        if($model->parent_id > 0)
                            return '(ID-'. $model->parent_id .') 内容- '.$model->parent->content;
                        else
                            return '此信息为评论。';
                    },

                ],
                'content',
            ],
        ]) ?>
    </div>
</div>
