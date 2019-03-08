<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;



/* @var $this yii\web\View */
/* @var $model app\models\rbac\AuthRule */

$this->title = '创建规则';
$this->params['breadcrumbs'][] = ['label' => '规则列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-rule-create">

    <div class="auth-rule-form box box-primary">
        <?php $form = ActiveForm::begin(); ?>
        <div class="box-body table-responsive">

            <?= $form->field($model, 'rulePath')->textInput(['maxlength' => true]) ?>

        </div>

        <div class="box-footer">
            <?= Html::submitButton('保存', ['class' => 'btn btn-success btn-flat']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>


</div>
