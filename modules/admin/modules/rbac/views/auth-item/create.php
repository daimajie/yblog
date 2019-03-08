<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\rbac\AuthItem */

$this->title = '添加项目';
$this->params['breadcrumbs'][] = ['label' => '项目列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
