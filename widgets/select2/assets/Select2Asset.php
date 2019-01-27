<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/1/21
 * Time : 19:02
 */

namespace app\widgets\select2\assets;


use yii\web\AssetBundle;

class Select2Asset extends AssetBundle
{
    public $sourcePath = '@widgets/select2/static';

    public $css = [
        'select2/css/select2.min.css'
    ];
    public $js = [
        'select2/js/select2.full.min.js',
        'select2/js/i18n/zh-CN.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        //'app\assets\LayerAsset',
    ];
}
