<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/2/26
 * Time : 14:03
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use app\widgets\HomeAlert as Alert;
use yii\helpers\Url;
use app\assets\LayerAsset;

LayerAsset::register($this);
?>
<!-- Content -->
<div class="row">

    <!-- post content -->
    <div class="col-lg-12 blog__content mb-30">

        <div class="title-wrap">
            <h3>账号设置</h3>
        </div>
        <div class="row">
            <div class="col-lg-3 sidebar sidebar--left setting-nav">
                <?= $this->render('_set_nav')?>
            </div>
            <div class="col-lg-9 blog__content mb-30 sidebar--right">
                <div class="row"><?= Alert::widget(['options' => ['class'=>'col-lg-12']]) ?></div>
                <div class="row mb-30">
                    <div class="col-md-6">

                        <?php $form = ActiveForm::begin([
                            'id' => 'setting-form',
                        ]); ?>

                        <label for="input-username">账号</label>
                        <input name="name" disabled="disabled" id="input-username" type="text" value="<?= Html::encode($model->username)?>">

                        <?php
                        echo Html::label('新邮箱','email');
                        echo $form->field($model, 'email', [
                            'options' => [
                                 'class' => 'search-form mb-3'
                            ],
                            'template' => "{input}<button type='button' id='send_btn' class='search-button btn btn-lg btn-color btn-button' style='width:120px;'>发送验证码</button>\n{error}",
                        ])->textInput([
                            'id' => 'email_val',
                            'placeholder'=>'邮箱',
                            'autocomplete'=>"off",
                            'style' => 'margin-bottom:0px;',
                        ]);

                        echo $form->field($model,'email_captcha', [
                            'options' => [
                                'class' => 'search-form mb-3'
                            ],
                        ])->textInput([
                            'placeholder'=>'邮箱验证码',
                            'autocomplete'=>"off",
                            'style' => 'margin-bottom:0px;',
                        ]);
                        ?>



                        <?= Html::submitButton('<span>提交保存</span>', ['class' => 'btn btn-sm', 'name' => 'login-button']) ?>
                        <?php ActiveForm::end(); ?>

                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- end col -->
</div>
<!-- end content -->


<?php
$sendEmailUrl = Url::to(['/home/index/send-email-captcha']);
$jsStr = <<<JS
    $('#send_btn').on('click', function(){
        
        var that = $(this), 
            val = $('#email_val').val();
        
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
