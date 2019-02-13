<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\content\Tag */

$this->title = '修改标签: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '标签列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改标签';
?>
<div class="tag-update">

    <?= $this->render('_form', [
        'model' => $model,
        'topic_id' => $topic_id
    ]) ?>

</div>
