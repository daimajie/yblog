<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\components\Helper;
use app\models\content\Article;
use app\models\content\Topic;
use yii\helpers\HtmlPurifier;

/* @var $this yii\web\View */
/* @var $model app\models\content\Article */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => '文章列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-view box box-primary">
    <div class="box-header">
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?php
        if( $model->status === Article::STATUS_RECYCLE )
            echo Html::a('彻底删除', ['discard', 'id' => $model->id], [
                'class' => 'btn btn-warning btn-flat',
                'data' => [
                    'confirm' => '您确定要删除该文章吗?',
                    'method' => 'post',
                ],
            ]);
        else
            echo Html::a('删除', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger btn-flat',
                'data' => [
                    'confirm' => '您确定要将该文章放置回收站吗?',
                    'method' => 'post',
                ],
            ]);
        ?>
    </div>
    <?php $form = ActiveForm::begin(['action' => Url::to(['view','id'=>$model->id])]); ?>
    <div class="box-body table-responsive no-padding">
        <?= DetailView::widget([
            'template' => '<tr><th width="120">{label}</th><td{contentOptions}>{value}</td></tr>',
            'model' => $model,
            'attributes' => [
                'id',
                'title',
                [
                    'format'=>'html',
                    'attribute' => 'image',
                    'value' => function($model){
                        if(!empty($model->image))
                            return Html::img(Helper::showImage($model->image), ['width'=>200]);
                        return '无图片';
                    },
                ],
                'brief',
                [
                    'attribute' => 'status',
                    'format' => 'raw',
                    'value' => function($model){
                        /*$input = Html::activeRadioList($model, 'status', [
                            Article::STATUS_NORMAL => '公示状态',
                            Article::STATUS_DRAFT => '草稿箱',
                            Article::STATUS_RECYCLE => '回收站',
                        ]);
                        $error = Html::error($model, 'status', ['class' => 'text-danger']);
                        return $input . $error;*/
                        $ret = [
                            Article::STATUS_NORMAL => '公示状态',
                            Article::STATUS_DRAFT => '草稿箱',
                            Article::STATUS_RECYCLE => '回收站',
                        ];

                        return '<strong>'.$ret[$model->status] . '</strong>';
                    }
                ],
                [
                    'attribute' => 'check',
                    'format' => 'raw',
                    'value' => function($model){
                        if($model->topic->secrecy == Topic::SECR_PRIVATE)
                            return '私密文章';

                        $input = Html::activeRadioList($model, 'check', [
                            Article::CHECK_WAIT => '待审核',
                            Article::CHECK_ADOPT => '审核通过',
                            Article::CHECK_DENIAL => '审核失败',
                        ]) ;
                        $error = Html::error($model, 'status', ['class' => 'text-danger']);
                        return $input . $error;
                    }
                ],
                'visited',
                'comment',
                [
                    'attribute' => 'topic_id',
                    'value' => function($model){
                        return $model->topic->name;
                    }
                ],
//                'user_id',
                [
                    'attribute' => 'content',
                    'format' => 'raw',
                    'value' => function($model){
                        return HtmlPurifier::process($model->content->content);
                    }
                ],
                [
                    'label' => '所用标签',
                    'value' => function($model){
                        return implode(', ',$model->getRelationTags());
                    }
                ],
                'created_at:datetime',
                'updated_at:datetime',
            ],
        ]) ?>
    </div>
    <div class="box-footer">
        <?= Html::submitButton('提交保存', ['class' => 'btn btn-info btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
