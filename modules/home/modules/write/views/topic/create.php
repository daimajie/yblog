<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/2/28
 * Time : 11:57
 */
use app\models\content\Topic;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\widgets\upload\Upload;
use app\widgets\Alert;

?>
<!-- Content -->
<div class="row">

    <!-- post content -->
    <div class="col-lg-12 blog__content mb-30">

        <div class="title-wrap">
            <h3>写作中心 - <small>创建话题</small></h3>
        </div>
        <div class="row">
            <div class="col-lg-12 blog__content mb-30 sidebar--right">
                <div class="row mb-30">
                    <div class="col-lg-12">
                        <?= Html::a('<span>话题列表</span>', ['index'], ['class' => 'btn btn-lg btn-color']) ?>
                    </div>
                </div>
                <div class="row"><?= Alert::widget(['options' => ['class'=>'col-lg-12']]) ?></div>
                <!-- content -->
                <div class="row">
                    <div class="col-md-12">
                        <?php $form = ActiveForm::begin([
                            'method' => 'post'
                        ]); ?>

                        <?= $form->field($model, 'name',[
                            'options' => [
                                'class' => 'mb-3'
                            ]
                        ])->textInput([
                            'maxlength' => true,
                            'autocomplete'=>"off",
                            'class' => ' mb-0'
                        ]) ?>

                        <?= $form->field($model, 'category_id',[
                            'options' => [
                                'class' => 'mb-3'
                            ]
                        ])->dropDownList($category_items,[
                            'prompt'=>'选择所属分类',
                            'maxlength' => true,
                            'class' => ' mb-0'
                        ])?>

                        <?= $form->field($model, 'image',[
                            'options' => [
                                'class' => 'mb-3'
                            ]
                        ])->widget(Upload::class,[
                            'info' => '',
                            'thumb' => [
                                'width' => 270,
                                'height' => 203
                            ],

                        ]) ?>


                        <?= $form->field($model, 'desc',[
                            'options' => [
                                'class' => 'mb-3'
                            ]
                        ])->textarea(['rows'=>5,'class'=>'mb-0']) ?>



                        <?php
                        $private = '';
                        $public = '';
                        if($model->secrecy == Topic::SECR_PRIVATE){
                            $private = 'checked="checked"';
                        }
                        if($model->isNewRecord || $model->secrecy == Topic::SECR_PUBLIC){
                            $public = 'checked="checked"';
                        }

                        ?>
                        <div class="radio mb-3">
                            <input <?= $public?> type="radio" class="radio-unput" name="<?= ($model->formName() . '[secrecy]')?>" id="radio2" value="2" >
                            <label for="radio2">公开话题</label>

                            <input <?= $private?> type="radio" class="radio-unput" name="<?= ($model->formName() . '[secrecy]')?>" id="radio1" value="1" >
                            <label for="radio1">私密话题</label>
                            <?= Html::error($model, 'secrecy')?>
                        </div>

                        <div class="radio mb-3">
                        <?php
                        if(!$model->isNewRecord):
                            $normal = '';
                            $finish = '';
                            if($model->isNewRecord || $model->status == Topic::STATUS_NORMAL){
                                $normal = 'checked="checked"';
                            }
                            if($model->status == Topic::STATUS_FINISH){
                                $finish = 'checked="checked"';
                            }
                            ?>

                                <input <?= $normal?> type="radio" class="radio-unput" name="<?= ($model->formName() . '[status]')?>" id="radio3" value="1" >
                                <label for="radio3">连载</label>

                                <input <?= $finish?> type="radio" class="radio-unput" name="<?= ($model->formName() . '[status]')?>" id="radio4" value="2" >
                                <label for="radio4">完结</label>


                        <?php endif;?>
                            <?= Html::error($model, 'status')?>
                        </div>

                        <?= Html::submitButton('<span>提交保存</span>', ['class' => 'btn btn-lg']) ?>

                        <?php ActiveForm::end(); ?>

                    </div>
                </div>


                <!-- \content -->
            </div>
        </div>
    </div>
    <!-- end col -->
</div>
<!-- end content -->
