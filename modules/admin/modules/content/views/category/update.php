<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\content\Category */

$this->title = '修改分类: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '分类列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改分类';
?>
<div class="category-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
