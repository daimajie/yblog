<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\member\SearchUser */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '创建用户';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index box box-primary">
    <div class="box-header with-border">

        <div class="pull-left">
            <?= Html::a('创建用户', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
        </div>
        <div class="pull-right">
            <?php echo $this->render('_search', [
                'model' => $searchModel,
            ]); ?>
        </div>
    </div>
    <div class="box-body table-responsive no-padding">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'username',
                'nickname',
                'email:email',
                'status',
                [
                    'attribute' => 'author',
                    'label' => '写作文章',
                    'value' => function($model){
                        if($model->author <= 0){
                            return '读者';
                        }
                        return $model->author;
                    }
                ],
                [
                    'attribute' => 'created_at',
                    'format' => ['date', 'php:Y-m-d']
                ],
                [
                    'attribute' => 'created_at',
                    'format' => ['date', 'php:Y-m-d']
                ],

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
