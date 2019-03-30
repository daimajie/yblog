<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\widgets\HomeAlert as Alert;


$this->title = '联系我';
?>
<div class="row">

    <!-- post content -->
    <div class="col-lg-12 blog__content mb-30">
        <div class="row justify-content-md-center">
            <div class="col-lg-8">
                <?= Alert::widget(['options' => ['class'=>'col-lg-12']]) ?>
            </div>
        </div>
        <div class="row justify-content-md-center">
            <div class="col-lg-8">

                <h3>联系我们。</h3>
                <p>别犹豫，联系一下。我们会尽快答复你。(如果想成为作者，写明主题即可。)</p>

                <!-- Contact Form -->
                <?php $form = ActiveForm::begin([
                    'id' => 'contact-form',
                    'options' => [
                        'class' => 'contact-form mt-30 mb-30'
                    ],
                    'method' => 'POST',
                ]); ?>

                    <?= $form
                        ->field($model, 'email')
                        ->textInput(['autocomplete'=>'off']) ?>

                    <?= $form
                        ->field($model, 'subject')
                        ->textInput(['autocomplete'=>'off']) ?>


                    <?= $form
                        ->field($model, 'message')
                        ->textarea(['rows'=>6]) ?>

                    <?= Html::submitButton('<span>提交</span>', [
                        'class' => 'btn btn-lg btn-color btn-button',
                    ]) ?>
                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div> <!-- end col -->

</div>
