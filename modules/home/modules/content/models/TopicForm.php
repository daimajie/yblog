<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/2/28
 * Time : 21:54
 */

namespace app\modules\home\modules\content\models;
use app\models\content\Topic;
use Yii;

class TopicForm extends Topic
{
    public function rules()
    {
        return [
            //设置状态
            [['status','secrecy'], 'in', 'range' => [1,2]],

            //crud
            [['secrecy', 'name', 'image','desc', 'category_id'], 'required'],

            [['category_id'], 'exist', 'targetClass' => Category::class, 'targetAttribute' => 'id'],

            [['name'], 'string', 'max' => 18],

            //每个用户不能在统一话题建立同名话题（回收站中的除外）
            [['name'], 'uniqueOnUser'],

            [['image'], 'string', 'max' => 125],

            [['desc'], 'string', 'max' => 225],

            [['secrecy'], 'secrecyLimit'], //私密话题限数
            [['status'], 'activeLimit'], //最多有5个连载话题
        ];


    }

    //检测用户创建私密话题是否达到上限
    public function secrecyLimit($attr){

        if($this->hasErrors()){
            return false;
        }

        //如果创建公开话题 直接通过
        if($this->secrecy == static::SECR_PUBLIC)
            return true;

        $count = static::find()->where([
            'user_id' => Yii::$app->user->getId(),
            'secrecy' => static::SECR_PRIVATE,
        ])->andWhere(['!=', 'status', static::STATUS_RECYCLE])
            ->count();

        $limit = Yii::$app->params['topic']['secrecy_limit'];

        if($count >= $limit){
            $this->addError($attr, '您已经创建了'.$count.'个私密话题了，已经够用了。');
            return false;
        }
        return true;
    }

    //每个用户最多同时又5个活跃活体，再不能创建
    public function activeLimit($attr){
        if($this->hasErrors())
            return false;


        $count = static::find()
            ->where([
                'user_id'=>Yii::$app->user->getId(),
                'status' => static::STATUS_NORMAL
            ])
            ->andWhere(['!=', 'status', static::STATUS_RECYCLE])
            ->count();
        $limit = Yii::$app->params['topic']['active_limit'];

        if($count >= $limit){
            $this->addError($attr, '您现在有'.$count.'个连载话题,快去完成吧。');
            return false;
        }
        return true;
    }
}