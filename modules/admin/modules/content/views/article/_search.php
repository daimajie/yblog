<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\content\Article;
use app\widgets\select2\Select2;
/* @var $this yii\web\View */
/* @var $model app\models\content\SearchArticle */
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
        Article::CHECK_WAIT => '待审核',
        Article::CHECK_ADOPT => '审核通过',
        Article::CHECK_DENIAL => '审核失败',
    ]) ?>

    <?= $form->field($model, 'category_id')->dropDownList($category,['prompt'=>'选择分类']) ?>

    <?= $form->field($model, 'topic_id')->widget(Select2::class,[
        //当下拉框改变后触发的js回调函数,参数一个，是选择后的id值
        'width' => '185px',
        'selectUrl' => '/admin/content/article/select'
    ]) ?>



    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary  btn-flat']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default  btn-flat']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
