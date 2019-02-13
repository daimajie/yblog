<?php

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\content\Article */

$this->title = '创建文章';
$this->params['breadcrumbs'][] = ['label' => '文章列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-create">

    <?= $this->render('_form', [
        'model' => $model,
        'tags' => $tags,
        'topic'=>$topic
    ]) ?>

</div>
