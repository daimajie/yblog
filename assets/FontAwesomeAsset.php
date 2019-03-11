<?php
/**
 * Created by PhpStorm.
 * User: yidashi
 * Date: 16/7/16
 * Time: 下午2:08
 */

namespace app\assets;


use yii\web\AssetBundle;

class FontAwesomeAsset extends AssetBundle
{
    public $basePath = '@webroot/static/libs';
    public $baseUrl = '@web/static/libs';

    public $css = [
        'font-awesome/css/font-awesome.min.css'
    ];
}