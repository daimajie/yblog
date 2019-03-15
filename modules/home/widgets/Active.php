<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/3/6
 * Time : 13:38
 */

namespace app\modules\home\widgets;


use app\models\member\User;
use yii\base\Widget;
use yii\behaviors\CacheableWidgetBehavior;

class Active extends Widget
{
    public $authors;


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
        $this->authors = User::find()
            ->select(['id','username','nickname','image'])
            ->where(['>', 'author', 0])
            ->orderBy(['author'=>SORT_DESC, 'updated_at'=>SORT_DESC])
            ->limit(9)
            ->asArray()
            ->all();

        return $this->render('active',[
            'authors' => $this->authors
        ]);
    }
}