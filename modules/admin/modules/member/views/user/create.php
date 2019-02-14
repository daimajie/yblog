<?php

/* @var $this yii\web\View */
/* @var $model app\models\member\User */

$this->title = '创建用户';
$this->params['breadcrumbs'][] = ['label' => '用户列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
