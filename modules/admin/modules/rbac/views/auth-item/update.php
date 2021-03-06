<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\rbac\AuthItem */

$this->title = '修改授权: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '权限列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->name]];
$this->params['breadcrumbs'][] = '修改授权';
?>
<div class="auth-item-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
