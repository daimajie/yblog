<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\content\SearchCategory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-search">

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


    <?= $form->field($model, 'name')->textInput(['placeholder'=>'搜索分类名称']) ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary  btn-flat']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default  btn-flat']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
