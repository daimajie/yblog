<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\ViewHelper;


/* @var $this yii\web\View */
/* @var $model app\models\setting\SEO */

$this->title = '信息设置';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="seo-create">

    <div class="seo-form box box-primary">
        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
        <div class="box-body table-responsive">

            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'keywords')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

            <div style="margin: 25px 0">
                <?= Html::img(ViewHelper::showImage($model->pc_logo), ['width'=>180,'height'=>49,'style'=>'background-color:#eee'])?>
                <?= $form->field($model, 'pc_logo_file')
                    ->fileInput()
                    ->hint('必须提供 ( 180*49 ) 尺寸的图片。');
                ?>
            </div>

            <div style="margin: 25px 0">
                <?= Html::img(ViewHelper::showImage($model->mobile_logo), ['width'=>123,'height'=>27,'style'=>'background-color:#eee'])?>
                <?= $form->field($model, 'mobile_logo_file')
                    ->fileInput()
                    ->hint('必须提供 ( 123*27 ) 尺寸的图片。')
                ?>
            </div>
            <div style="margin: 25px 0">
                <?= Html::img(ViewHelper::showImage($model->qrcode), ['width'=>80,'height'=>80,'style'=>'background-color:#eee'])?>
                <?= $form->field($model, 'qrcode_file')
                    ->fileInput()
                    ->hint('必须提供 ( 300*300 ) 尺寸的图片。') ?>
            </div>

            <?= $form->field($model, 'about')->textarea(['rows' => 8]) ?>

        </div>
        <div class="box-footer">
            <?= Html::submitButton('提交保存', ['class' => 'btn btn-success btn-flat']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

</div>
