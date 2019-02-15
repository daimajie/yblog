<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\content\Tag */

$this->title = '创建标签';
$this->params['breadcrumbs'][] = ['label' => '标签列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tag-create">

    <?= $this->render('_form', [
    'model' => $model,
    'topic_id' => $topic_id
    ]) ?>

</div>
