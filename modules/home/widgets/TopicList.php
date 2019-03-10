<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/3/10
 * Time : 19:18
 */

namespace app\modules\home\widgets;


use yii\base\Exception;
use yii\base\Widget;
use yii\behaviors\CacheableWidgetBehavior;
use app\models\content\Topic;
use Yii;

class TopicList extends Widget
{
    public $duration;
    public $dependency = null;
    public $user_id;

    public $secrecy = false;


    //显示的信息
    public $title = '作者话题';


    public function behaviors()
    {
        return [
            /*[
                'class' => CacheableWidgetBehavior::class,
                'cacheDuration' => $this->duration,
                'cacheDependency' => $this->dependency
            ]*/

        ];
    }

    public function init()
    {
        parent::init();
        if(!isset($this->user_id)) throw new Exception('传递参数错误。');
        if(!isset($this->duration)) $this->duration = 3600 * 24;//默认缓存1天
    }

    public function run()
    {
        $topics = [];
        $show = false;

        if($this->secrecy){
            //获取作者私密话题
            if(!Yii::$app->user->isGuest && Yii::$app->user->id == $this->user_id){
                $topics = Topic::getSecrecyTopicByUser($this->user_id);
                $show = true;
            }


        }else{
            $topics = Topic::getActiveTopicsByUser($this->user_id, 5);
        }

        if( $this->secrecy === false || ( $this->secrecy &&  $show) ){
            return $this->render('topic-list',[
                'topics' => $topics,
                'title' => $this->title,
                'more' => !$this->secrecy,
                'user_id' => $this->user_id
            ]);
        }

    }
}