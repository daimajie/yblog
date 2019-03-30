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
use app\widgets\upload\Upload;

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
                    <div class="col-md-12">
                        <?php $form = ActiveForm::begin(['id' => 'setting-form']); ?>


                        <?= $form->field($profile, 'qrcode',[
                            'options' => [
                                'class' => 'mb-3'
                            ]
                        ])->widget(Upload::class,[
                            'info' => '', //提示信息
                            'show' => false, //是否显示输入框
                            'name' => 'qrcode', //input name值
                            'thumb' => [ //截取尺寸
                                'width' => 300,
                                'height' => 300
                            ],
                            'uploadPath' => 'qrcode' //上传方法

                        ])->label('打赏二维码') ?>





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
