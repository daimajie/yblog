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
                        <?php $form = ActiveForm::begin(['id' => 'setting-form']); ?>

                        <label for="input-username">账号</label>
                        <input name="name" disabled="disabled" id="input-username" type="text" value="<?= Html::encode($model->username)?>">

                        <label for="input-email">邮箱</label>
                        <input name="name" disabled="disabled" id="input-email" type="text" value="<?= Html::encode($model->email)?>">


                        <?= $form
                            ->field($model, 'password')
                            ->passwordInput([]) ?>

                        <?= $form
                            ->field($model, 're_password')
                            ->passwordInput([]) ?>


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
