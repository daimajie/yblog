<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/2/28
 * Time : 11:57
 */
use app\models\content\Topic;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\widgets\Alert;
use app\components\ViewHelper;
use yii\helpers\Url;
use app\assets\LayerAsset;

LayerAsset::register($this);
?>
<!-- Content -->
<div class="row">

    <!-- post content -->
    <div class="col-lg-12 blog__content mb-30">

        <div class="title-wrap">
            <h3>写作中心 - <small>话题详情</small></h3>
        </div>
        <div class="row">
            <div class="col-lg-12 blog__content mb-30 sidebar--right">
                <div class="row mb-30">
                    <div class="col-lg-12">
                        <?= Html::a('<span>话题列表</span>', ['index'], ['class' => 'btn btn-lg btn-color']) ?>
                        <?= Html::a('<span>文章列表</span>', ['article/index','id'=>$model->id],['class' => 'btn btn-lg']);?>
                    </div>
                </div>
                <div class="row"><?= Alert::widget(['options' => ['class'=>'col-lg-12']]) ?></div>
                <!-- content -->
                <div class="row">
                    <div class="col-md-12">
                        <?= DetailView::widget([
                            'template' => '<tr><th width="120">{label}</th><td{contentOptions}>{value}</td></tr>',
                            'model' => $model,
                            'attributes' => [
                                'name',
                                [
                                    'format'=>'html',
                                    'attribute' => 'image',
                                    'value' => function($model){
                                        if(!empty($model->image))
                                            return Html::img(ViewHelper::showImage($model->image), ['width'=>150]);
                                        return '暂无图片';
                                    },
                                ],
                                'desc',
                                'count',
                                [
                                    'attribute' => 'status',
                                    'format' => 'raw',
                                    'value' => function($model){
                                        $tmp = [
                                            Topic::STATUS_NORMAL => '连载',
                                            Topic::STATUS_FINISH => '完结',
                                            Topic::STATUS_RECYCLE => '回收站',
                                        ];
                                        return $tmp[$model->status];
                                    }
                                ],
                                [
                                    'attribute' => 'category_id',
                                    'value' => function($model){
                                        return '《 ' . $model->category->name . ' 》';
                                    }
                                ],
                                [
                                    'attribute' => 'check',
                                    'format' => 'raw',
                                    'value' => function($model){
                                        $tmp = [
                                            Topic::CHECK_WAIT => '待审核',
                                            Topic::CHECK_ADOPT => '审核通过',
                                            Topic::CHECK_DENIAL => '审核失败',
                                        ];
                                        return $tmp[$model->check];
                                    }
                                ],
                                [
                                    'attribute' => 'secrecy',
                                    'value' => function($model){
                                        $tmp = ['私密话题','公开话题'];
                                        return $tmp[$model->secrecy - 1];
                                    }
                                ],
                                [
                                    'attribute' => 'user_id',
                                    'value' => function($model){
                                            return ViewHelper::username($model->user->username, $model->user->nickname);
                                    }
                                ],
                                [
                                    'attribute' => 'tags',
                                    'label' => '包含标签',
                                    'format' => 'raw',
                                    'value' => function($model){
                                        $tags = $model->getTags()->asArray()->all();
                                        $ht = '';
                                        foreach($tags as $tag){
                                            $ht .= '<a class="operate-tag" data-id="'. $tag['id'] .'" href="javascript:void(0);"><span class="badge badge-secondary">'. $tag['name'] .'</span></a> ';
                                        }
                                        $ht .= '<a id="add_tag" href="javascript:void(0);"><span class="badge badge-secondary">+</span></a> ';
                                        return $ht;
                                    }
                                ],
                                [
                                    'attribute' => 'created_at',
                                    'format' => ['date', 'php:Y-m-d']
                                ],
                            ],
                        ]) ?>

                    </div>
                </div>


                <!-- \content -->
            </div>
        </div>
    </div>
    <!-- end col -->
</div>
<!-- end content -->
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
    operate_tag.create('{$createTagUrl}');
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
?>
<!-- Modal -->
<div class="modal fade" id="operate-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="operate-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="operate-body" class="modal-body">
                ...
            </div>
        </div>
    </div>
</div>


