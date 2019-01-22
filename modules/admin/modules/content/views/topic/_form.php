<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\widgets\upload\Upload;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Topic */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="topic-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>

    <div class="box-body table-responsive">

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'image')->widget(Upload::class) ?>

        <?= $form->field($model, 'desc')->textarea(['rows'=>5]) ?>

        <?= $form->field($model, 'secrecy')->radioList([
                '1' => '私有话题',
                '2' => '公开话题'
        ]) ?>
        

    </div>
    <div class="box-footer">
        <?= Html::submitButton('提交保存', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
