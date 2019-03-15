<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $model app\models\member\UserForm */
$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => '日志列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-form-view box box-primary">
    <div class="box-header">
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger btn-flat',
            'data' => [
                'confirm' => '您确定要删除该项吗?',
                'method' => 'post',
            ],
        ]) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?php
        echo DetailView::widget([
            'template' => '<tr><th width="180">{label}</th><td{contentOptions}>{value}</td></tr>',
            'model' => $model,
            'attributes' => [
                'id',
                'level',
                'category',
                'log_time:datetime',
                'prefix',
                'message:text',
            ],
        ]);
        ?>
    </div>
</div>
