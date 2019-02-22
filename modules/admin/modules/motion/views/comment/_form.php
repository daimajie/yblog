<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\motion\Comment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="comment-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'content')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'parent_id')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'user_id')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'created_at')->textInput(['maxlength' => true]) ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
