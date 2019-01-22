<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\Helper;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Topic */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '话题列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="topic-view box box-primary">
    <div class="box-header">
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger btn-flat',
            'data' => [
                'confirm' => '您确定要删除该话题吗?',
                'method' => 'post',
            ],
        ]) ?>
    </div>
    <?php $form = ActiveForm::begin(['action' => Url::to(['status','id'=>$model->id])]); ?>
    <div class="box-body table-responsive no-padding">
        <?= DetailView::widget([
            'template' => '<tr><th width="120">{label}</th><td{contentOptions}>{value}</td></tr>',
            'model' => $model,
            'attributes' => [
                'id',
                'name',
                'image',
                [
                    'format'=>'html',
                    'attribute' => 'image',
                    'value' => function($model){
                        return Html::img(Helper::showImage($model->image), ['width'=>150]);
                    },
                ],
                'desc',
                'count',
                [
                    'attribute' => 'status',
                    'format' => 'raw',
                    'value' => function($model){
                        return Html::activeRadioList($model, 'status', [
                                '1' => '正常',
                                '2' => '完结',
                                '3' => '冻结',
                        ]) ;
                    }
                ],
                [
                    'attribute' => 'check',
                    'format' => 'raw',
                    'value' => function($model){
                        return Html::activeRadioList($model, 'check', [
                            '1' => '待审核',
                            '2' => '审核通过',
                            '3' => '审核失败',
                        ]) ;
                    }
                ],
                [
                    'attribute' => 'secrecy',
                    'value' => function($model){
                        $tmp = ['私密话题','公开话题'];
                        return $tmp[$model->secrecy - 1];
                    }
                ],
                'user_id',
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
