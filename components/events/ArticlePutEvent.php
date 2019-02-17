<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/2/15
 * Time : 20:51
 */

namespace app\components\events;
use yii\base\Event;

class ArticlePutEvent extends Event
{
    public $status;
    public $check;
    public $topic_id;

    public $oldStatus;
    public $oldCheck;
    public $oldTopic_id;

    public $article_id;

    public $user_id;

}