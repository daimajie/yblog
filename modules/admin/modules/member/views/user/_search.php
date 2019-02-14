<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\member\SearchUser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'class' => 'form-inline',
        ],
        'fieldConfig'=>[
            'template' =>'{input}'
        ],
    ]); ?>


    <?= $form->field($model, 'username')->textInput(['placeholder'=>'用户名']) ?>

    <?= $form->field($model, 'nickname')->textInput(['placeholder'=>'昵称']) ?>

    <?= $form->field($model, 'author')->dropDownList([
        '-1' => '读者',
        '0' => '作者'
    ],['prompt'=>'搜索角色']) ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary  btn-flat']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default  btn-flat']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
