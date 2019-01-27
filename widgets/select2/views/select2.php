<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/1/24
 * Time : 13:23
 */
use yii\helpers\Html;
use yii\helpers\Url;
?>

<?= Html::activeDropDownList($model, 'topic_id',[],[
    'class' => "form-control",
    'id' => $id
])?>

<?php
$token =  Yii::$app->request->getCsrfToken();

$selectUrl = Yii::$app->urlManager->createAbsoluteUrl(['/admin/content/article/select']);
$js = <<<SCRIPT

$("#{$id}").select2({ 
    placeholder:'请搜索并选择所属话题', 
    allowClear:true,
    minimumInputLength:3,
    language: "zh-CN",
    width:'{$width}',
    ajax: {
        url: '{$selectUrl}',
        dataType: 'json',
        data: function (params) {
          var query = {
            search: params.term,
          }
          return query;
        },
        processResults: function (data) {
          return {
            results: data.data
          };
        }
        
    },
    
//选择之后触发searchAfterJsFun事件
}).on('change', function(){
    var fun = {$searchAfterJsFun};
    fun.call(null, $(this).val());    
});

//设置默认选中的项目
var selected = {$selected};
if( selected.id ){
    $("#{$id}").html('<option value="'+selected.id+'" selected="selected">'+selected.name+'</option>');
}


SCRIPT;
$this->registerJs($js);




