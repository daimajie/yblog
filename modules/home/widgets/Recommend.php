<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/3/6
 * Time : 15:13
 */

namespace app\modules\home\widgets;
use app\models\content\Article;
use app\models\motion\Comment;
use yii\base\Widget;
use yii\behaviors\CacheableWidgetBehavior;

class Recommend extends Widget
{
    public $comment;
    public $byComment;
    public $byVisited;

    const ART_TBL = '{{%article}}';

    public function behaviors()
    {
        return [
            [
                'class' => CacheableWidgetBehavior::class,
                'cacheDuration' => 0,
                'cacheDependency' => [
                    'class' => 'yii\caching\DbDependency',
                    'sql' => 'SELECT MAX(created_at) FROM ' . self::ART_TBL,
                ],
            ]
        ];
    }

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $query = Article::find()
            ->where([
                'check' => Article::CHECK_ADOPT,   //审核通过的文章（可以刨除私密话题的文章）
                'status' => Article::STATUS_NORMAL,//公示文章
            ])
            ->andWhere(['>=', 'created_at', strtotime('-30day')])//一个月内
            ->limit(5)
            ->asArray();

        $this->byComment = $query->orderBy(['comment' => SORT_DESC,'created_at'=>SORT_DESC])->all(); //评论最多
        $this->byVisited = $query->orderBy(['visited' => SORT_DESC,'created_at'=>SORT_DESC])->all(); //阅读数最多

        $this->comment = Comment::find()
            ->with(['user'])
            ->orderBy(['created_at'=>SORT_DESC])
            ->limit(5)
            ->asArray()
            ->all();

        return $this->render('recommend',[
            'byComment' => $this->byComment,
            'byVisited' => $this->byVisited,
            'comment'=> $this->comment
        ]);
    }
}