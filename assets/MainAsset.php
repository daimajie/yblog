<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/2/18
 * Time : 20:14
 */

namespace app\assets;


use yii\web\AssetBundle;

class MainAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'static/assets/css/bootstrap.min.css',
        'static/assets/css/font-icons.css',
        'static/assets/css/style.css',

        ['static/assets/img/favicon.ico','rel'=>"shortcut icon"],
        ['static/assets/img/apple-touch-icon.png','rel'=>"apple-touch-icon"],
        ['static/assets/img/apple-touch-icon-72x72.png','rel'=>"apple-touch-icon", 'sizes'=>"72x72"],
        ['static/assets/img/img/apple-touch-icon-114x114.png','rel'=>"apple-touch-icon", 'sizes'=>"114x114"],
        ['https://fonts.googleapis.com/css?family=Open+Sans:400,600,700']
    ];
    public $js = [
        'static/assets/js/bootstrap.min.js',
        'static/assets/js/lazysizes.min.js',
        'static/assets/js/easing.min.js',
        'static/assets/js/owl-carousel.min.js',
        'static/assets/js/jquery.newsTicker.min.js',
        'static/assets/js/modernizr.min.js',
        'static/assets/js/scripts.js',

    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];

    //定义按需加载JS方法
    public static function addScript($view, $jsfile) {
        $view->registerJsFile($jsfile, ['depends' => static::class]);
    }
    //定义按需加载css方法
    public static function addCss($view, $cssfile) {
        $view->registerCssFile($cssfile, ['depends' => static::class]);
    }
}