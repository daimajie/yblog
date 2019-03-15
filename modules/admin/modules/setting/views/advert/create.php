<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\setting\Advert */

$this->title = '广告管理';
$this->params['breadcrumbs'][] = ['label' => 'Adverts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="advert-create">

    <div class="advert-form box box-primary">
        <?php $form = ActiveForm::begin(['enableClientValidation' => false]); ?>
        <div class="box-body table-responsive">

            <?= $form->field($model, 'switch')->radioList([
                '1' => '开启',
                '0' => '关闭',
            ],[]) ?>

            <?= $form->field($model, 'advert_bar')->textarea(['rows' => 6]) ?>

            <?= $form->field($model, 'advert')->textarea(['rows' => 6]) ?>

        </div>
        <div class="box-footer">
            <?= Html::submitButton('提交保存', ['class' => 'btn btn-success btn-flat']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>


</div>
