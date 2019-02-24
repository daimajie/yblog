<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\motion\Comment */

$this->title = '修改评论: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '评论列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comment-update">

    <div class="comment-form box box-primary">
        <?php $form = ActiveForm::begin(); ?>
        <div class="box-body table-responsive">

            <?= $form->field($model, 'content')->textarea([
                    'maxlength' => true,
                    'rows' => 15
                ]) ?>

        </div>
        <div class="box-footer">
            <?= Html::submitButton('保存', ['class' => 'btn btn-success btn-flat']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

</div>
