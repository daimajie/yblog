<?php

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\content\Topic */

$this->title = '新建话题';
$this->params['breadcrumbs'][] = ['label' => '话题列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="topic-create">

        <?= $this->render('_form', [
            'model' => $model,
            'category_items' => $category_items
        ]) ?>

</div>
