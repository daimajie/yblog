<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/2/24
 * Time : 19:33
 */

namespace app\assets;

use yii\web\AssetBundle;

class LayDateAsset  extends AssetBundle
{
    public $basePath = '@webroot/static/libs';
    public $baseUrl = '@web/static/libs';
    public $css = [
    ];
    public $js = [
        'laydate/laydate.js',
    ];
    public $depends = [
        'dmstr\web\AdminLteAsset',
    ];


}