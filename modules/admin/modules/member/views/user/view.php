<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\grid\GridView;
use app\models\rbac\AuthItem;
use app\components\ViewHelper;

/* @var $this yii\web\View */
/* @var $model app\models\member\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => '用户列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-4">
        <div class="user-view box box-primary">
            <div class="box-header">
                <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
                <?= Html::a('删除', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger btn-flat',
                    'data' => [
                        'confirm' => '您确定要删除该用户吗?',
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
            <?php $form = ActiveForm::begin([
                'action' => Url::to(['view','id'=>$model->id]),
            ]); ?>
            <div class="box-body table-responsive no-padding">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'id',
                        'username',
                        'nickname',
                        'email:email',
                        [
                            'attribute' => 'image',
                            'format' => 'image',
                            'value' => function($model){
                                return ViewHelper::showImage($model->image);
                            },
                        ],
                        'status',
                        [
                            'attribute' => 'author',
                            'label' => '写作文章',
                            'format' => 'raw',
                            'value' => function($model){
                                if($model->author >= 0){
                                    return $model->author;
                                }

                                $input =  Html::activeRadioList($model, 'author', [
                                    '-1' => '读者',
                                    '0' => '作者',
                                ]);
                                $error = Html::error($model, 'author', ['class' => 'text-danger']);
                                return $input . $error;
                            }
                        ],

                        //'auth_key',
                        //'password_hash',
                        //'password_reset_token',
                        'created_at:datetime',
                        'updated_at:datetime',
                    ],
                ]) ?>
            </div>
            <div class="box-footer">
                <?= Html::submitButton('提交保存', [
                        'class' => 'btn btn-info btn-flat ' . ($model->author >= 0 ? 'hide' : '')
                ]) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <div class="col-lg-8">
        <?php
        $form = ActiveForm::begin([
            'action' => ['assignment'],
            'method' => 'POST'
        ]);
        echo Html::hiddenInput('user_id', $model->id);
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
                            'label' => '拥有权限(角色)',//authItemChildren
                            'format' => 'raw',
                            'value' => function($model) use ($rolesName){
                                $checked = false;
                                if( in_array($model->name, $rolesName) ){
                                    $checked = true;
                                }

                                $input = Html::checkbox('roles[]', $checked, ['value'=>$model->name]);
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

