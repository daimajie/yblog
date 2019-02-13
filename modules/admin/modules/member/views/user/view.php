<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\member\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => '用户列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view box box-primary">
    <div class="box-header">
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger btn-flat',
            'data' => [
                'confirm' => '您确定要删除该用户吗?',
                'method' => 'post',
            ],
        ]) ?>
    </div>
    <?php $form = ActiveForm::begin([
        'action' => Url::to(['view','id'=>$model->id]),
    ]); ?>
    <div class="box-body table-responsive no-padding">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'username',
                'nickname',
                'email:email',
                'image',
                'status',
                [
                    'attribute' => 'author',
                    'label' => '写作文章',
                    'format' => 'raw',
                    'value' => function($model){
                        if($model->author >= 0){
                            return $model->author;
                        }

                        $input =  Html::activeRadioList($model, 'author', [
                            '-1' => '读者',
                            '0' => '作者',
                        ]);
                        $error = Html::error($model, 'author', ['class' => 'text-danger']);
                        return $input . $error;
                    }
                ],

                'auth_key',
                'password_hash',
                'password_reset_token',
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
