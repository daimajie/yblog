<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\content\Tag */
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
        <?= Html::button('保存', ['class' => 'btn btn-success btn-flat','id'=>'submit_btn']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php
//禁止重复提交
$js = <<<SCRIPT
$('#submit_btn').on('click', function(){
    if($(this).prop('disabled')) return;
    $(this).prop({
        disabled:"disabled"
    });
    $('#form_operate').submit();
});
SCRIPT;
$this->registerJs($js);

