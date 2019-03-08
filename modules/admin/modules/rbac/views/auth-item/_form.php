<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\rbac\AuthItem;

/* @var $this yii\web\View */
/* @var $model app\models\rbac\AuthItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="auth-item-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'type')->dropDownList([
            AuthItem::TYPE_ROLE => '创建角色',
            AuthItem::TYPE_ROUTER => '添加路由',
        ],[
            'prompt' => '选择项目类型'
        ]) ?>

        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'rule_name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'data')->textarea(['rows' => 3]) ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('提交保存', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
