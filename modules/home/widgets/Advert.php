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
use app\models\setting\Advert as AdvertModel;

class Advert extends Widget
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
        $model = $model = AdvertModel::find()->select(['switch','advert'])->limit(1)->one();
        if(!empty($model) && $model->switch){
            return $this->render('advert',[
                'content' => $model->advert
            ]);
        }
        return null;
    }
}