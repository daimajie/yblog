<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/3/8
 * Time : 18:11
 */

namespace app\modules\home\modules\write\models;


use app\models\content\Tag;
use app\modules\home\modules\write\models\TopicForm as Topic;
use Yii;

class TagForm extends Tag
{
    public function rules()
    {
        return array_merge(parent::rules(), [
            //[['topic_id'], 'exist', 'targetClass' => Topic::class, 'targetAttribute' => ['topic_id' => 'id']],
            [['topic_id'], 'isOwner']
        ]);
    }

    //标签所属的话题是否是自己所有
    public function isOwner($attr){
        if($this->hasErrors())
            return false;

        $owner = Topic::find()
            ->where([
                'id' => $this->topic_id,
                'user_id' => Yii::$app->user->id
            ])
            ->one();
        if(!$owner){
            $this->addError($attr, '当前的话题您无权操作。');
            return false;
        }
        return true;
    }


}