<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/2/26
 * Time : 14:03
 */
use yii\widgets\ActiveForm;
use app\widgets\upload\assets\UploadAsset;
use app\widgets\upload\Upload;
use yii\helpers\Html;
use app\widgets\HomeAlert as Alert;

UploadAsset::register($this);
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

                        <?php $form = ActiveForm::begin([
                            'id' => 'avatar_form',
                        ]); ?>

                        <?= $form->field($model, 'image')->widget(Upload::class,[
                            'info' => '',
                            'show' => false,
                            'thumb' => [
                                'width' => 100,
                                'height' => 100
                            ]
                        ]) ?>

                        <?= Html::submitButton('<span>提交保存</span>', [
                            //'class' => 'btn btn-sm',
                            'name' => 'login-button',
                            'style'=>"margin-left:6px;"
                        ]) ?>

                        <?php ActiveForm::end(); ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- end col -->
</div>
<!-- end content -->


