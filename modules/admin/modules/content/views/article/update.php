<?php

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\content\Article */

$this->title = '修改文章: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => '文章列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改文章';
?>
<div class="article-update">

    <?= $this->render('_form', [
        'model' => $model,
        'tags' => $tags,
        'topic' => $topic
    ]) ?>

</div>
