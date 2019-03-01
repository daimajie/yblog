<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\content\Tag */

$validationUrl = ['validate-form'];
if (!$model->isNewRecord) {
    $validationUrl['id'] = $model->id;
}

?>
<!-- content -->
<div class="row">
    <div class="col-md-12">
        <?php $form = ActiveForm::begin([
            'id' => 'form_operate',
            'enableAjaxValidation' => true,
            'validationUrl' => $validationUrl
        ]); ?>


        <?= $form->field($model, 'name',[
            'options' => [
                'class' => 'mb-3'
            ]
        ])->textInput([
            'maxlength' => true,
            'autocomplete'=>"off",
            'class' => ' mb-0'
        ]) ?>

        <?= $form->field($model, 'topic_id')->hiddenInput([
            'maxlength' => true,
            'value' => $topic_id
        ])->label(false);
        ?>

        <?= Html::button('保存', ['class' => 'btn btn-lg btn-color','id'=>'submit_btn']) ?>

        <?php ActiveForm::end(); ?>

    </div>
</div>
<!-- \content -->
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
