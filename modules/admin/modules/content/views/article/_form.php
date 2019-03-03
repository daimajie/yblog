<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\widgets\upload\Upload;
use app\widgets\select2\Select2;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\content\Article */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="article-form box box-primary">
    <?php $form = ActiveForm::begin([
        'id' => 'article_form',
        //'enableClientValidation' => false
    ]); ?>
    <div class="box-body table-responsive">
        <?= $form->field($model, 'status')->hiddenInput([
                'id' => 'article_status'
        ])->label(false)?>

        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'brief')->textarea(['rows' => 3]) ?>

        <?= $form->field($model, 'image')->widget(Upload::class,[
            'info' => '请选择一张图片作为文章封面.<br/>(推荐尺寸 270 * 203)',
            'thumb' => [
                'width' => 270,
                'height' => 203
            ]
        ]) ?>

        <?= $form->field($model, 'topic_id')->widget(Select2::class,[
                //当下拉框改变后触发的js回调函数,参数一个，是选择后的id值
                'searchAfterJsFun' => 'fillData.fill',
                'selected' => $topic,
                'selectUrl' => '/admin/content/article/select'
        ]) ?>

        <?= $form->field($model, 'art_content')->textarea(['rows' => 16]) ?>

        <?= $form->field($model, 'new_tags')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'arr_tags')->checkboxList($tags, [
            'id'=>'select_tags',
            'class' => 'checkbox'
        ])->hint('请先选择话题。',['class'=>!empty($tags)?'hidden':'show','id'=>'select_tags_hint'])?>



    </div>
    <div class="box-footer">
        <?php $btn = $this->context->action->id == 'update' ? '点击保存' : '立即发布'?>
        <?= Html::button($btn, ['class' => 'btn btn-success btn-flat','id'=>'do_publish']) ?>
        <?= Html::button('存为草稿', ['class' => 'btn btn-info btn-flat','id'=>'do_draft']) ?>
        <?= Html::resetButton('重置',['class'=>'btn btn-warning btn-flat'])?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php
$getTagsUrl = Url::to(['tag/get-tags']);

$fieldName = $model->formName() . '[arr_tags][]';
$js = <<<SCRIPT
    var fillData = {
        //当前数据模型
        'name' : "{$fieldName}",
        
        'selector' : '#select_tags',
        
        'hintSelector' : '#select_tags_hint',
        
        //选择话题后的回调
        fill: function( tid ){
            //ajax请求标签
            $.ajax({
                url : "{$getTagsUrl}",
                data : {topic_id:tid},
                type:'GET',
                success : function(d){
                    if(d.errcode === 0){
                        //console.log(d.errmsg);
                    }
                    //填充数据
                    fillData.checkBox(d.data);
                    return;
                }
            });
        },
        //创建复选框
        checkBox : function(items){
            //没有数据 提示可以创建标签
            if( items.length <= 0){
                $(fillData.hintSelector)
                .text('暂无可用标签，您可以选择新建标签。')
                .removeClass('hidden')
                .addClass('show');
                
                //清空标签
                $(fillData.selector).html('');
                
                
                return;
            }else{
                $(fillData.hintSelector)
                .removeClass('show')
                .addClass('hidden');
            }
            
            //清空标签
            $(fillData.selector).html('');
            
            let str = '';
            $.each(items, function(k, v){
                 
                str += '<label><input type="checkbox" name="'+ fillData.name +'" value="'+ k +'">'+ v +'</label> ';
            });
            
            
            $(fillData.selector).html(str);
            
               
        }
        
        
    }

    //表单提交(立即发布或存为草稿)
    $('#do_publish').on('click', function(){
        $('#article_status').val(1);
        $('#article_form').submit();
    });
    $('#do_draft').on('click', function(){
        $('#article_status').val(2);
        $('#article_form').submit();
    });
    
    
SCRIPT;
$this->registerJs($js);



