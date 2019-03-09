<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/3/7
 * Time : 11:05
 */

namespace app\modules\home\widgets;
use yii\base\Widget;
use yii\behaviors\CacheableWidgetBehavior;

class contact extends Widget
{
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
    }

    public function run()
    {

        return $this->render('contact');
    }
}