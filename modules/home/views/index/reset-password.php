<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;




$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];

$this->title = '重置密码';
?>

<div class="login-box">
    <div class="login-logo">
        <a href="/"><b>DAIMAJIE</b></a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg"><?= $this->title?></p>

        <?php
        if(Yii::$app->session->hasFlash('success'))
            echo '<p class="bg-success">' .Yii::$app->session->getFlash('success'). '</p>';

        if(Yii::$app->session->hasFlash('error'))
            echo '<p class="bg-error">' .Yii::$app->session->getFlash('error'). '</p>';
        ?>

        <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>

        <?= $form
            ->field($model, 'new_password', $fieldOptions2)
            ->label(false)
            ->passwordInput(['placeholder' => $model->getAttributeLabel('new_password')]) ?>

        <?= $form
            ->field($model, 're_password', $fieldOptions2)
            ->label(false)
            ->passwordInput(['placeholder' => $model->getAttributeLabel('re_password')]) ?>




        <div class="row">
            <div class="col-xs-8"></div>
            <!-- /.col -->
            <div class="col-xs-4">
                <?= Html::submitButton('提交保存', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
            </div>
            <!-- /.col -->
        </div>


        <?php ActiveForm::end(); ?>

        <div class="social-auth-links text-center">
            <p>- OR -</p>
        </div>
        <!-- /.social-auth-links -->

        <a target="_blank" href="<?= Url::to(['index/login'])?>" class="text-center">返回登录页</a>

    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->
