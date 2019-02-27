<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/1/21
 * Time : 11:50
 */
use yii\helpers\Url;
use yii\helpers\Html;
use app\components\Helper;


$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@widgets/upload/static');
$type = $show ? 'text' : 'hidden';
?>


<?= Html::activeInput($type, $model, 'image',[
    'class' => "form-control disabled",
    'readonly' => true,
    'id' => $id
])?>
<div id="uploader" class="upload-wrap clearfix">
    <div class="col-sm-12 col-md-6 col-lg-4">
        <div id="fileBox" class="upload-image col-sm-6 col-xs-8">
            <?php if(!empty($model->image)):?>
                <img src="<?= Helper::showImage($model->image)?>" alt="上传图片">
            <?php else:?>
                <img src="<?= $directoryAsset?>/image/bg.jpg" alt="上传图片">
            <?php endif;?>
        </div>
        <div class="upload-btn col-xs-12 col-sm-6">
            <div>
                <div id="filePicker">选择图片</div>
                <p><?= $info?></p>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <p id="response_msg" class="text-danger"></p>
    </div>
</div>

<?php
$token =  Yii::$app->request->getCsrfToken();
$upUrl = Url::to(['upload']);

$js = <<<UPLOADER
$(function(){
    var fileBox;
    
    var uploader = WebUploader.create({
        auto: true,
        swf: '{$directoryAsset}/webuploader-0.1.5/Uploader.swf',
        fileVal: '{$name}',
    
        server: '{$upUrl}',
    
        pick: {
            'id' : '#filePicker',
            'multiple' : false,
        },
    
        accept: {
            title: 'Images',
            extensions: 'gif,jpg,jpeg,bmp,png',
            mimeTypes: 'image/*'
        },
        
        formData: {  
            _csrf : "$token",  
        },
        
        //限制尺寸
        whLimit:{width:[100,1000], height:[100, 1000]}
    });
    
    //加入队列执行
    uploader.on( 'fileQueued', function( file ) {
        uploader.makeThumb( file, function( error, src ) {
            fileBox = $('#fileBox');
            
            if( error ){
                fileBox.html('<img src="#" alt="图片不能预览。" />');
                return;
            }
        
            $('#fileBox').html('<img src="'+ src +'">');
            
        }, {$thumb['width']}, {$thumb['height']} );
        
        
    });
    
    //上传成功
    uploader.on( 'uploadSuccess', function( file, response) {
       
       if(response.code === 0){
           //填充数据
           $('#{$id}').val(response.url);
           
           fileBox.removeClass('upload-fail').addClass('upload-success');
       } else {
           //显示错误信息
           $('#response_msg').text(response.msg);
           
           fileBox.removeClass('upload-success').addClass('upload-fail');
       }
       
       layer.msg(response.msg);
       
       
    });
    
    //上传失败
    uploader.on( 'uploadError', function( file, response) {
        layer.msg('链接服务器失败，请重试。');
        
        fileBox.removeClass('upload-success').addClass('upload-fail');
    });

});

UPLOADER;

$this->registerJs($js);

