<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\widgets\upload\Upload;

/* @var $this yii\web\View */
/* @var $model app\models\content\Topic */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="topic-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>

    <div class="box-body table-responsive">

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'category_id')->dropDownList($category_items,['prompt'=>'选择所属分类'])?>

        <?= $form->field($model, 'image')->widget(Upload::class,[
            'info' => '请选择一张图片作为话题封面.<br/>(推荐尺寸 390 * 293)',
            'thumb' => [
                'width' => 390,
                'height' => 293
            ]
        ]) ?>

        <?= $form->field($model, 'desc')->textarea(['rows'=>5]) ?>

        <?= $form->field($model, 'secrecy')->radioList([
                '1' => '私有话题',
                '2' => '公开话题'
        ]) ?>
        

    </div>
    <div class="box-footer">
        <?= Html::submitButton('提交保存', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
