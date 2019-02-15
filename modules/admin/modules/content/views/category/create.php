<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\content\Category */

$this->title = '创建分类';
$this->params['breadcrumbs'][] = ['label' => '分类列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
