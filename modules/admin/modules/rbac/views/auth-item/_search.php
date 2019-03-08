<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\rbac\AuthItem;

/* @var $this yii\web\View */
/* @var $model app\models\content\SearchTopic */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="topic-search">

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

    <?= $form->field($model, 'name')->textInput(['placeholder'=>'权限名称']) ?>

    <?= $form->field($model, 'type')->dropDownList([
        AuthItem::TYPE_ROLE => '角色',
        AuthItem::TYPE_ROUTER => '路由',
    ],['prompt'=>'类型搜索']) ?>


    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary  btn-flat']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default  btn-flat']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
