<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\admin\models\Topic;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\SearchTopic */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="topic-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'class' => 'form-inline',
        ],
        'fieldConfig'=>[
            'template' =>'{input}'
        ],
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['placeholder'=>'话题名称']) ?>

    <?php echo $form->field($model, 'category_id')->dropDownList($category_items,['prompt'=>'选择所属分类']) ?>

    <?php echo $form->field($model, 'status')->dropDownList([
            '' => '使用状态',
            Topic::STATUS_NORMAL => '正常',
            Topic::STATUS_FINISH => '完结',
    ]) ?>

    <?php echo $form->field($model, 'check')->dropDownList([
        '' => '审核状态',
        '1' => '等待审核',
        '2' => '审核通过',
        '3' => '审核失败',
    ]) ?>


    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary  btn-flat']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default  btn-flat']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
