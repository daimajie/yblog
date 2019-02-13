<?php

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\content\Topic */

$this->title = '修改话题: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '话题列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $model->name;
?>
<div class="topic-update">

    <?= $this->render('_form', [
        'model' => $model,
        'category_items'=>$category_items
    ]) ?>

</div>
