<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/2/28
 * Time : 11:57
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\widgets\upload\Upload;
use app\widgets\HomeAlert as Alert;
use app\widgets\select2\Select2;
use yii\helpers\Url;
use app\widgets\ueditor\UEditor;

$this->title = '创建文章';
?>
<!-- Content -->
<div class="row">

    <!-- post content -->
    <div class="col-lg-12 blog__content mb-30">

        <div class="title-wrap">
            <h3>写作中心 - <small>创建文章</small></h3>
        </div>
        <div class="row">
            <div class="col-lg-12 blog__content mb-30 sidebar--right">
                <div class="row mb-30">
                    <div class="col-lg-12">
                        <?= Html::a('<span>文章列表</span>', ['index'], ['class' => 'btn btn-lg btn-color']) ?>
                    </div>
                </div>
                <div class="row"><?= Alert::widget(['options' => ['class'=>'col-lg-12']]) ?></div>
                <!-- content -->
                <div class="row">
                    <div class="col-md-12">
                        <?php $form = ActiveForm::begin([
                            'method' => 'post',
                            'id' => 'article_form',
                            'fieldConfig' => [

                            ]
                        ]); ?>

                        <!-- 状态 -->
                        <?= $form->field($model, 'status')->hiddenInput([
                            'id' => 'article_status'
                        ])->label(false)?>


                        <!-- 标题 -->
                        <?= $form->field($model, 'title',[
                            'options' => ['class' => 'mb-3']
                        ])->textInput([
                            'maxlength' => true,
                            'class' => 'mb-0',
                            'autocomplete' => 'off'
                        ]) ?>

                        <!-- 简介 -->
                        <?= $form->field($model, 'brief',[
                            'options' => ['class' => 'mb-3']
                        ])->textarea([
                            'rows' => 3,
                            'class' => 'mb-0'
                        ]) ?>

                        <!-- 封面 -->
                        <?= $form->field($model, 'image',[
                            'options' => ['class' => 'mb-3']
                        ])->widget(Upload::class,[
                            'info' => '',
                            'thumb' => [
                                'width' => 270,
                                'height' => 203
                            ]
                        ]) ?>

                        <!-- 话题 -->
                        <?= $form->field($model, 'topic_id',[
                            'options' => ['class' => 'mb-3']
                        ])->widget(Select2::class,[
                            //当下拉框改变后触发的js回调函数,参数一个，是选择后的id值
                            'searchAfterJsFun' => 'fillData.fill',
                            'selected' => $topic,
                            'selectUrl' => '/home/write/article/select'
                        ])->hint('！请注意，只能搜索到连载话题.',['class'=>'text-info']) ?>

                        <!-- 内容 -->
                        <?php
                        echo $form->field($model, 'art_content',[
                            'options' => ['class' => 'mb-3']
                        ])->widget(UEditor::class,[
                            'saveUrl' => ['/home/write/article/upload-file'],
                            'clientOptions' => [
                                'class' => 'mb-0'
                            ]
                        ]);

                        echo Html::error($model, 'art_content');
                        ?>

                        <!-- 新建标签 -->
                        <?= $form->field($model, 'new_tags',[
                            'options' => ['class' => 'mb-3']
                        ])->textInput([
                            'maxlength' => true,
                            'class' => 'mb-0',
                            'autocomplete' => 'off'
                        ]) ?>

                        <div class="mb-3">
                            <?= Html::label($model->getAttributeLabel('arr_tags'))?>
                            <div id="select_tags" class="form-check-inline">
                                <?php
                                foreach($tags as $key => $val):
                                    $checked = '';
                                    if(in_array($key, $model->arr_tags)) $checked = 'checked="checked"';
                                ?>
                                    <span class="mr-3">
                                        <input <?= $checked?> name="<?= $model->formName() . '[arr_tags][]'?>" class="form-check-input" type="checkbox" id="<?= $val .'_'. $key?>" value="<?= $key?>">
                                        <label class="form-check-label" for="<?= $val .'_'. $key?>"><?= $val?></label>
                                    </span>


                                <?php endforeach;?>
                                <?= Html::error($model,$model->getAttributeLabel('arr_tags'))?>
                            </div>
                            <small><span id="select_tags_hint" class="text-danger">如果没有标签可以选择创建</span></small>
                        </div>

                        <div class="box-footer">
                            <?php $btn = $this->context->action->id == 'update' ? '点击保存' : '立即发布'?>
                            <?= Html::button($btn, ['class' => 'btn btn-sm btn-color','id'=>'do_publish']) ?>
                            <?= Html::button('存为草稿', ['class' => 'btn btn-sm btn-dark','id'=>'do_draft']) ?>
                            <?= Html::resetButton('重置',['class'=>'btn btn-sm btn-light '])?>
                        </div>

                        <?php ActiveForm::end(); ?>

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
$getTagsUrl = Url::to(['tag/get-tags']);
$checkedTags = json_encode($model->arr_tags);

$fieldName = $model->formName() . '[arr_tags][]';
$js = <<<SCRIPT
    //表单提交(立即发布或存为草稿)
    $('#do_publish').on('click', function(){
        $('#article_status').val(1);
        $('#article_form').submit();
    });
    $('#do_draft').on('click', function(){
        $('#article_status').val(2);
        $('#article_form').submit();
    });


    var fillData = {
        //当前数据模型
        'name' : "{$fieldName}",
        
        'selector' : '#select_tags',
        
        'hintSelector' : '#select_tags_hint',
        
        'checked' : JSON.parse('{$checkedTags}'),
        
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
                .text('暂无可用标签，您可以选择新建标签。');
                $(fillData.selector).html('');
                
                return;
            }else{
                $(fillData.hintSelector)
                .text('一篇文章最多可选择三个标签。');
            }
            
            //清空标签
            $(fillData.selector).html('');
            
            let str = '';
            $.each(items, function(k, v){
                 
                 var checked = '';
                 if($.inArray(k, fillData.checked) >= 0){
                    checked = 'checked="checked"';
                 }
                 str += '<span class="mr-3"><input ' +checked+ ' name="{$fieldName}" class="form-check-input" type="checkbox" id="'+ v +'_'+ k +'" value="'+ k +'"><label class="form-check-label" for="'+ v +'_'+ k +'">'+ v +'</label></span>';
            });
            
            $(fillData.selector).html(str);
            
               
        }
        
        
    }
    
    
SCRIPT;
$this->registerJs($js);
