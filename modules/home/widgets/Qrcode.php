<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/3/7
 * Time : 11:13
 */

namespace app\modules\home\widgets;
use yii\base\Widget;
use yii\behaviors\CacheableWidgetBehavior;

class Qrcode extends Widget
{
    public $image;
    public $title;

    public function behaviors()
    {
        return [
            /*[
                'class' => CacheableWidgetBehavior::class,
                'cacheDuration' => 3600 * 24, //缓存一天
            ]*/

        ];
    }

    public function init()
    {
        parent::init();


        if(empty($this->title))
            $this->title = '扫码关注';
    }

    public function run()
    {
        if(empty($this->image)) return false;

        return $this->render('qrcode',[
            'title' => $this->title,
            'image' => $this->image,

        ]);
    }
}