<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/2/28
 * Time : 21:54
 */

namespace app\modules\home\modules\write\models;
use app\models\content\Article;
use app\models\content\Topic;
use Yii;
use app\models\content\Category;

class TopicForm extends Topic
{
    public function rules()
    {
        return [
            //设置状态
            [['status','secrecy'], 'in', 'range' => [1,2]],
            [['status'], 'default', 'value' => 1],

            //crud
            [['secrecy', 'name', 'image', 'desc', 'category_id'], 'required'],

            [['category_id'], 'exist', 'targetClass' => Category::class, 'targetAttribute' => 'id'],

            [['name'], 'string', 'max' => 18],

            //每个用户不能在同一话题建立同名话题（回收站中的除外）
            [['name'], 'uniqueOnUser'],

            [['image'], 'string', 'max' => 125],

            [['desc'], 'string', 'max' => 225],

            [['secrecy'], 'secrecyLimit'],  //私密话题限数
            [['status'], 'activeLimit','when'=>function($model){ return $model->isNewRecord;}],    //创建时检测最多有5个连载话题
            [['status'], 'checkStatus','when'=>function($model){ return !$model->isNewRecord;}]
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
        ])->andWhere(['!=', 'status', static::STATUS_RECYCLE])//刨除回收站的话题
          ->count();

        $limit = Yii::$app->params['topic']['secrecy_limit'];

        if($count >= $limit){
            $this->addError($attr, '您已经创建了'.$count.'个私密话题了，已经够用了。');
            return false;
        }
        return true;
    }

    //每个用户最多同时n个活跃活体，再不能创建
    public function activeLimit($attr){
        if($this->hasErrors())
            return false;

        if($this->status != static::STATUS_NORMAL){
            $this->addError($attr, '新建话题必须为连载状态');
            return false;
        }

        //如果建立私有话题 直接通过
        if($this->secrecy == static::SECR_PRIVATE)
            return true;

        $count = static::find()
            ->where([
                'user_id'=>Yii::$app->user->getId(),
                'status' => static::STATUS_NORMAL,
                'secrecy' => static::SECR_PUBLIC
            ])
            //->andWhere(['!=', 'status', static::STATUS_RECYCLE])
            ->count();
        $limit = Yii::$app->params['topic']['active_limit'];

        if($count >= $limit){
            $this->addError($attr, '您现在有'.$count.'个连载话题,先去完成吧。');
            return false;
        }
        return true;
    }

    //修改话题时检测 包含文章数目 及活跃话题数目
    public function checkStatus($attr){

        $currentStatus = (int) $this->status;
        $oldStatus = (int) $this->getDirtyAttributes(['status']) ? $this->getOldAttribute('status') : $this->status;

        //状态不改变什么也不做
        if($currentStatus === $oldStatus){
            return true;
        }

        if($oldStatus === static::STATUS_NORMAL){
            //检测包含文章数
            $count = Article::find()
                ->where([
                    'topic_id' => $this->id,
                    //'user_id' => Yii::$app->user->id,

                ])->andWhere(['!=', 'status', static::STATUS_RECYCLE])//去掉回收站的
            ->count();
            if($count < 5){
                $this->addError($attr, '话题至少包含5篇文章才能设为完结状态。');
                return false;
            }
        }

        if($oldStatus === static::STATUS_FINISH){
            //检测活跃话题数
            $count = static::find()
                ->where([
                    'user_id'=>Yii::$app->user->getId(),
                    'status' => static::STATUS_NORMAL,
                    'secrecy' => static::SECR_PUBLIC
                ])
                //->andWhere(['!=', 'status', static::STATUS_RECYCLE])
                ->count();
            $limit = Yii::$app->params['topic']['active_limit'];

            if($count >= $limit){
                $this->addError($attr, '您现在有'.$count.'个连载话题,先去完成吧。');
                return false;
            }
        }
        return true;
    }
}