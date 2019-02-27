<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;


$this->title = 'DAIMAJIE - 登录页';

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
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

        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

        <?= $form
            ->field($model, 'username', $fieldOptions1)
            ->label(false)
            ->textInput(['placeholder' => $model->getAttributeLabel('username')]) ?>

        <?= $form
            ->field($model, 'password', $fieldOptions2)
            ->label(false)
            ->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>

        <?= $form->field($model,'captcha',[
            'options' => [
                'tag'=>'div',
                'class' => 'form-group has-feedback',
            ],
            'template' => "{label}\n{input}\n{error}<span class=\"glyphicon glyphicon-picture  form-control-feedback\"></span>",
        ])->widget(yii\captcha\Captcha::className(),[
            'captchaAction'=>'/home/index/captcha',
            'options' => ['placeholder'=>'验证码','class'=>'form-control','autocomplete'=>"off"],
            'imageOptions'=>[
                'alt'=>'点击换图',
                'title'=>'点击换图',
                'style'=>'cursor:pointer',
            ],
            'template' => '{input} {image}',
        ])->label(false);?>

        <div class="row">
            <div class="col-xs-8">
                <?= $form->field($model, 'rememberMe')->checkbox()->label('记住我') ?>
            </div>
            <!-- /.col -->
            <div class="col-xs-4">
                <?= Html::submitButton('登录', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
            </div>
            <!-- /.col -->
        </div>


        <?php ActiveForm::end(); ?>

        <div class="social-auth-links text-center">
            <p>- OR -</p>
            <!--<a href="#" class="btn btn-block btn-social btn-facebook btn-flat">
                <i class="fa fa-facebook"></i>
                Sign in using Facebook
            </a>
            <a href="#" class="btn btn-block btn-social btn-google-plus btn-flat">
                <i class="fa fa-google-plus"></i>
                Sign in using Google+
            </a>-->
        </div>
        <!-- /.social-auth-links -->

        <a href="<?= Url::to(['index/forget'])?>">忘记密码 ?</a><br>
        <a href="<?= Url::to(['index/register'])?>" class="text-center">注册一个新账户</a>

    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->
