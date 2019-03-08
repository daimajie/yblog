<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\rbac\AuthItem;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model app\models\rbac\AuthItem */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '权限列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row">
    <div class="col-lg-4">
        <div class="auth-item-view box box-primary">
            <div class="box-header">
                <?= Html::a('修改', ['update', 'id' => $model->name], ['class' => 'btn btn-primary btn-flat']) ?>
                <?= Html::a('删除', ['delete', 'id' => $model->name], [
                    'class' => 'btn btn-danger btn-flat',
                    'data' => [
                        'confirm' => '您确定要删除该项目吗?',
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
            <div class="box-body table-responsive no-padding">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'name',
                        [
                            'attribute' => 'type',
                            'value' => function($model){
                                $tmp = [
                                    AuthItem::TYPE_ROLE => '角色',
                                    AuthItem::TYPE_ROUTER => '路由'
                                ];
                                return $tmp[$model->type];
                            }
                        ],
                        'description:ntext',
                        'rule_name',
                        'data',
                        'created_at:datetime',
                        'updated_at:datetime',

                    ],
                ]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <?php
        $form = ActiveForm::begin([]);
        echo Html::hiddenInput('role_name', $model->name);
        ?>
        <div class="auth-item-view box box-primary">
            <div class="box-body table-responsive no-padding">
                <?= GridView::widget([
                    'id' => 'grid',
                    'showFooter' => true,
                    'dataProvider' => $routerDataProvider,
                    'layout' => "{items}\n{summary}\n{pager}",
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'footerOptions' => [
                                'hidden' => false
                            ],
                        ],
                        [
                            'label' => '拥有权限(路由)',//authItemChildren
                            'format' => 'raw',
                            'value' => function($model) use($routers){
                                $checked = false;
                                if( in_array($model->name, $routers) ){
                                    $checked = true;
                                }

                                $input = Html::checkbox('children[]', $checked, ['value'=>$model->name]);
                                return $input;
                            },
                            'footerOptions' => ['colspan' => 5],
                            'footer' => Html::submitButton('提交',['class'=>'btn btn-info btn-flat'])
                        ],
                        [
                            'attribute' => 'name',
                            'footerOptions' => [
                                'hidden' => true
                            ],
                        ],
                        [
                            'attribute' => 'type',
                            'value' => function($model){
                                $tmp = [
                                    AuthItem::TYPE_ROLE => '角色',
                                    AuthItem::TYPE_ROUTER => '路由'
                                ];
                                return $tmp[$model->type];
                            },
                            'footerOptions' => [
                                'hidden' => true
                            ],
                        ],
                        [
                            'attribute' => 'description',
                            'format' => ['ntext'],
                            'footerOptions' => [
                                'hidden' => true
                            ],
                        ],
                        [
                            'attribute' => 'rule_name',
                            'footerOptions' => [
                                'hidden' => true
                            ],
                        ],
                        [
                            'attribute' => 'created_at',
                            'format' => ['date', 'php:Y-m-d'],
                            'footerOptions' => [
                                'hidden' => true
                            ],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
        <?php ActiveForm::end()?>
    </div>
</div>


