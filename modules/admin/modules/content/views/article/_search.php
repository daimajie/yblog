<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\admin\models\Article;
use app\widgets\select2\Select2;
/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\SearchArticle */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="article-search">
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

    <?= $form->field($model, 'check')->dropDownList([
        '' => '全部文章',
        ARTICLE::CHECK_WAIT => '待审核',
        ARTICLE::CHECK_ADOPT => '审核通过',
        ARTICLE::CHECK_DENIAL => '审核失败',
    ]) ?>

    <?= $form->field($model, 'topic_id')->widget(Select2::class,[
        //当下拉框改变后触发的js回调函数,参数一个，是选择后的id值
        'width' => '185px'
    ]) ?>



    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary  btn-flat']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default  btn-flat']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
