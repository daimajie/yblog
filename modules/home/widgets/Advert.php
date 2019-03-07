<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/3/7
 * Time : 10:55
 */

namespace app\modules\home\widgets;
use yii\base\Widget;
use yii\behaviors\CacheableWidgetBehavior;

class Advert extends Widget
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
        return $this->render('advert');
    }
}