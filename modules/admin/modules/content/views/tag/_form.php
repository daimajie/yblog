<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Tag */
/* @var $form yii\widgets\ActiveForm */
$validationUrl = ['validate-form'];
if (!$model->isNewRecord) {
    $validationUrl['id'] = $model->id;
}
?>

<div class="tag-form box box-primary">
    <?php $form = ActiveForm::begin([
        'id' => 'form_operate',
        'enableAjaxValidation' => true,
        'validationUrl' => $validationUrl
    ]); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'topic_id')->hiddenInput([
                'maxlength' => true,
                'value' => $topic_id
            ])->label(false);
        ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
