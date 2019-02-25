<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use app\assets\LayerAsset;

LayerAsset::register($this);


$this->title = 'DAIMAJIE - 注册页';

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

        <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>

        <?= $form
            ->field($model, 'username', $fieldOptions1)
            ->label(false)
            ->textInput(['placeholder' => '用户名'])?>

        <?= $form
            ->field($model, 'password', $fieldOptions2)
            ->label(false)
            ->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>

        <?= $form
            ->field($model, 're_password', $fieldOptions2)
            ->label(false)
            ->passwordInput(['placeholder' => $model->getAttributeLabel('re_password')]) ?>

        <?=
        $form->field($model, 'email', ['options'=>[
            'id' => 'email_val',
            'class'=>'clearfix'
        ],
            'template' => "<div id='send_email' class='form-inline'>{label}\n{input}<a id='send_btn' class='btn btn-default'>发送邮箱验证码</a>\n{error}</div>",
        ])->textInput([
            'placeholder' => '邮箱',
            'style' => 'display:inline-block'
        ])->label(false);
        ?>


        <?= $form
            ->field($model, 'email_captcha', $fieldOptions1)
            ->label(false)
            ->textInput(['placeholder' => $model->getAttributeLabel('email_captcha'),'autocomplete'=>"off"]) ?>





        <div class="row">
            <div class="col-xs-8">
            </div>
            <!-- /.col -->
            <div class="col-xs-4">
                <?= Html::submitButton('点击注册', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
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

        <a href="<?= Url::to(['index/login'])?>" class="text-center">已有账号，返回登录。</a>

    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->

<?php
$sendEmailUrl = Url::to(['index/send-email-captcha']);
$jsStr = <<<JS
    $('#send_btn').on('click', function(){
        
        var that = $(this), 
            val = $('#send_email input').val();
        
        if(val.length <= 0) return;
        
        if(that.hasClass('disabled')) return;
        
        //放置重复点击 间隔1.5秒
        that.addClass('disabled');
        
        $.ajax({
            url : "$sendEmailUrl",
            type : 'POST',
            data : {email : val},
            success : function(d){
                if(d.errcode === 0){
                    //邮件发送成功
                    setTimer(that);
                }else{
                    
                    setTimeout(function(){
                        that.removeClass('disabled');
                    },1500);
                    layer.msg(d.message); 
                }
                
            }
        });
        return false;
        
    });
    //定时器
    var timer = null;
    function setTimer(obj){
        clearInterval(timer);
        
        if( !obj.hasClass('disabled') ) obj.addClass('disabled');
        
        obj.text('已发送邮件...');
        
        var scond = 60;
            timer = setInterval(function(){
            scond--;
            
            if(scond <= 0){
                obj.text('发送邮箱验证码').removeClass('disabled');
                clearInterval(timer);
            }else{
                obj.text(' - ' + scond + ' - ');
            }
        },1000);
    }
JS;
$this->registerJs($jsStr);
?>
