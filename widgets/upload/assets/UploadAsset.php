<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/1/21
 * Time : 11:29
 */

namespace app\widgets\upload\assets;

use yii\web\AssetBundle;

class UploadAsset extends AssetBundle
{
    public $sourcePath = '@widgets/upload/static';

    public $css = [
        'webuploader-0.1.5/webuploader.css',
        'css/upload.css'
    ];
    public $js = [
        'webuploader-0.1.5/webuploader.min.js',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'app\assets\LayerAsset',
    ];


}