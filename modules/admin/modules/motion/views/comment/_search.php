<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\assets\LayDateAsset;

LayDateAsset::register($this);
/* @var $this yii\web\View */
/* @var $model app\models\motion\SearchComment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="comment-search">

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

    <?= $form->field($model, 'start_time')->textInput([
        'placeholder'=>'开始时间',
        'class'=>'form-control',
        'autocomplete'=>"off",
        'id' => 'start_time'

    ]) ?>

    <?= $form->field($model, 'end_time')->textInput([
        'placeholder'=>'结束时间',
        'class'=>'form-control',
        'autocomplete'=>"off",
        'id' => 'end_time'
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary  btn-flat']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default  btn-flat']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$js = <<<JS
    laydate.render({
        elem: '#start_time'
    });
    laydate.render({
        elem: '#end_time'
    });

JS;
$this->registerJs($js);
?>