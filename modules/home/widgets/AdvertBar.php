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
use app\models\setting\Advert;

class AdvertBar extends Widget
{
    public function behaviors()
    {
        return [
           /* [
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

        $model = $model = Advert::find()->select(['switch','advert_bar'])->limit(1)->one();
        if(!empty($model) && $model->switch){
            return $this->render('advertBar',[
                'content' => $model->advert_bar
            ]);
        }
        return null;
    }
}