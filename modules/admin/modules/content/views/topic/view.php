<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\Helper;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use app\assets\LayerAsset;

LayerAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Topic */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '话题列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="topic-view box box-primary">
    <div class="box-header">
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger btn-flat',
            'data' => [
                'confirm' => '您确定要删除该话题吗?',
                'method' => 'post',
            ],
        ]) ?>
    </div>
    <?php $form = ActiveForm::begin([
            'action' => Url::to(['view','id'=>$model->id]),
    ]); ?>
    <div class="box-body table-responsive no-padding">
        <?= DetailView::widget([
            'template' => '<tr><th width="120">{label}</th><td{contentOptions}>{value}</td></tr>',
            'model' => $model,
            'attributes' => [
                'id',
                'name',
                [
                    'format'=>'html',
                    'attribute' => 'image',
                    'value' => function($model){
                        return Html::img(Helper::showImage($model->image), ['width'=>150]);
                    },
                ],
                'desc',
                'count',
                [
                    'attribute' => 'status',
                    'format' => 'raw',
                    'value' => function($model){
                        $input =  Html::activeRadioList($model, 'status', [
                                '1' => '正常',
                                '2' => '完结',
                                '3' => '冻结',
                        ]);
                        $error = Html::error($model, 'status', ['class' => 'text-danger']);
                        return $input . $error;
                    }
                ],
                [
                    'attribute' => 'check',
                    'format' => 'raw',
                    'value' => function($model){
                        $input = Html::activeRadioList($model, 'check', [
                            '1' => '待审核',
                            '2' => '审核通过',
                            '3' => '审核失败',
                        ]);
                        $error = Html::error($model, 'check', ['class' => 'text-danger']);

                        return $input . $error;
                    }
                ],
                [
                    'attribute' => 'secrecy',
                    'value' => function($model){
                        $tmp = ['私密话题','公开话题'];
                        return $tmp[$model->secrecy - 1];
                    }
                ],
                'user_id',
                [
                    'attribute' => 'tags',
                    'label' => '包含标签',
                    'format' => 'raw',
                    'value' => function($model){
                        $tags = $model->getTags()->asArray()->all();
                        $ht = '';
                        foreach($tags as $tag){
                            $ht .= '<a class="operate-tag" data-id="'. $tag['id'] .'" href="javascript:void(0);"><span class="label label-default">'. $tag['name'] .'</span></a> ';
                        }
                        $ht .= '<a id="add_tag" href="javascript:void(0);"><span class="label label-default"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></span></a> ';
                        return $ht;
                    }
                ],


                'created_at:datetime',
                'updated_at:datetime',
            ],
        ]) ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('提交保存', ['class' => 'btn btn-info btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php
$deleteTagUrl = Url::to(['tag/delete'], true);
$createTagUrl = Url::to(['tag/create','topic_id'=>$model->id], true);
$updateTagUrl = Url::to(['tag/update','topic_id'=>$model->id], true);
$js = <<<OPERATE
$('.operate-tag').on('click', function(){
    var that = this;
    var tid = $(this).data('id');
    
    //验证一下数据
    if(tid <= 0)
        return;

    //询问框
    layer.confirm('请选择对该标签的操作。', {
      btn: ['删除','编辑']
    }, function(){
        layer.confirm('您确定要删除该标签吗？', {
          btn: ['确定','取消']
        }, function(){
          operate_tag.delete('{$deleteTagUrl}', tid, that);
        }, function(){
          layer.close(layer.index);
        });
    }, function(){
        //编辑
        operate_tag.init('#operate-modal', '标签编辑');
        operate_tag.update('{$updateTagUrl}', tid);
    });
    
});
//点击添加按钮
$('#add_tag').on('click', function(){
    //编辑
    operate_tag.init('#operate-modal', '创建标签');
    operate_tag.update('{$createTagUrl}');
    
});

/*tag operate*/
var operate_tag = {
    //模态框选择器
    selector:'',

    //模态框初始化
    init:function(selector, title){
        operate_tag.selector = selector;
        $(operate_tag.selector).modal({});
        $(operate_tag.selector).find('.modal-title').html(title);
    },
    
    //删除标签
    delete: function(deleteUrl, tid, that){
        $.ajax({
            url: deleteUrl,
            type: 'POST',
            data: {id:tid},
            success : function(d){
                if(d.errcode !== 0){
                    //删除成功
                    layer.msg(d.errmsg, {icon: 2});
                    return;
                }
                //删除成功
                layer.close(layer.index);
                //移除标签
                $(that).remove();
                return;
            }
        });
    },

    //标签编辑
    update : function(updateUrl, tid, type){
        type = type || 'GET';
    
        $.ajax({
            url: updateUrl,
            type: type,
            data: {id:tid},
            success: function(d){
                $(operate_tag.selector).find('.modal-body').html(d);
            }
        });
    },
    
    //创建标签
    create : function(createUrl, type){
        type = type || 'GET';
        $.ajax({
            url: createUrl,
            type: type,
            success: function(d){
                $(operate_tag.selector).find('.modal-body').html(d);
            }
        });
    }
};



OPERATE;
$this->registerJs($js);
//模态框
Modal::begin([
    'id' => 'operate-modal',
    'header' => '<h4 class="modal-title"></h4>',
]);
Modal::end();

