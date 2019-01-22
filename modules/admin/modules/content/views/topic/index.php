<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\SearchTopic */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '话题列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="topic-index box box-primary">
    <div class="box-header with-border">
        <div class="pull-left">
            <?= Html::a('创建话题', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
        </div>
        <div class="pull-right">
            <?php echo $this->render('_search', ['model' => $searchModel]); ?>
        </div>
    </div>
    <div class="box-body table-responsive no-padding">

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'name',
                'count',
                [
                    'attribute' => 'status',
                    'enableSorting' => false,
                    'value' => function($model){
                        $tmp = ['正常','完结','冻结'];
                        return $tmp[$model->status - 1];
                    }
                ],
                [
                    'attribute' => 'check',
                    'enableSorting' => false,
                    'value' => function($model){
                        $tmp = ['待审核','审核通过','审核失败'];
                        return $tmp[$model->check - 1];
                    }
                ],
                [
                    'attribute' => 'secrecy',
                    'enableSorting' => false,
                    'value' => function($model){
                        $tmp = ['私密话题','公开话题'];
                        return $tmp[$model->secrecy - 1];
                    }
                ],
                [
                    'attribute' => 'user_id',
                    'enableSorting' => false,
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
