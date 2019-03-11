<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/3/11
 * Time : 22:30
 */

namespace app\modules\home\widgets;
use yii\base\Widget;
use yii\behaviors\CacheableWidgetBehavior;

class AdvertBar extends Widget
{
    public function behaviors()
    {
        return [
            [
                'class' => CacheableWidgetBehavior::class,
                'cacheDuration' => 3600 * 24, //缓存一天
            ]
        ];
    }


    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return $this->render('advertBar');
    }
}