<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/1/21
 * Time : 19:02
 */

namespace app\assets;


use yii\web\AssetBundle;

class LayerAsset extends AssetBundle
{
    public $basePath = '@webroot/static/libs/layer';
    public $baseUrl = '@web/static/libs/layer';

    public $css = [

    ];
    public $js = [
        'layer.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
