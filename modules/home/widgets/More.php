<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/3/6
 * Time : 11:14
 */

namespace app\modules\home\widgets;
use app\models\content\Article;
use yii\base\Widget;
use yii\behaviors\CacheableWidgetBehavior;

class More extends Widget
{
    public $articles;

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
        $this->articles = Article::find()
            ->with(['user'])
            ->where([
                'check' => Article::CHECK_ADOPT,   //审核通过的文章（可以刨除私密话题的文章）
                'status' => Article::STATUS_NORMAL,//公示文章
            ])
            ->andWhere(['>=', 'created_at', strtotime('-30day')])//一个月内评论最多
            ->orderBy(['comment' => SORT_DESC])
            ->limit(3)
            ->asArray()
            ->all();

        return $this->render('more',[
            'articles' => $this->articles
        ]);
    }
}