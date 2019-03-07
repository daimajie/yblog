<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/3/6
 * Time : 12:17
 */

namespace app\modules\home\widgets;


use app\models\content\Topic;
use yii\base\Widget;
use yii\behaviors\CacheableWidgetBehavior;

class HotTopic extends Widget
{
    public $topics;

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
        $this->topics = Topic::find()
            ->select(['id','name', 'image', 'count'])
            ->where([
                'check' => Topic::CHECK_ADOPT, //审核通过的话题
                'secrecy' => Topic::SECR_PUBLIC,//公开话题
            ])
            ->andWhere(['!=', 'status', Topic::STATUS_RECYCLE])//非回收站的
            //->andWhere(['>=', 'count', 5])//一个月内评论最多
            ->orderBy(['updated_at'=>SORT_DESC,'count' => SORT_DESC])
            ->limit(6)
            ->asArray()
            ->all();

        return $this->render('hot-topic',[
            'topics' => $this->topics
        ]);
    }
}