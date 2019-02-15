<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\content\Category */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'desc')->textarea(['maxlength' => true]) ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('提交保存', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
