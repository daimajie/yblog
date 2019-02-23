<?php

namespace app\models\motion;

use app\models\content\Article;
use app\models\member\User;
use Yii;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\data\Pagination;
use yii\helpers\Html;
use app\components\ViewHelper;
use yii\widgets\LinkPager;

/**
 * This is the model class for table "{{%comment}}".
 *
 * @property string $id 主键
 * @property string $content 评论内容
 * @property string $parent_id 回复
 * @property string $user_id 用户
 * @property string $created_at 评论时间
 */
class Comment extends \yii\db\ActiveRecord
{

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => null
            ],
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'user_id',
                'updatedByAttribute' => null,
            ]
        ];
    }



    /**
     * 定义事务操作 (在插入 删除时开启事务)
     * @return array
     */
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL
        ];
    }

    //文章评论数维护
    //新增评论 加一
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        //如果是新增评论 计数
        if($insert){
            $this->commentCounter(1);
        }
    }

    //删除评论加一
    public function afterDelete()
    {
        parent::afterDelete();

        //判断是否拥有回复数据
        $count = 1;
        if($this->parent_id == 0){
            $affect = self::deleteAll(['parent_id' => $this->id]);
            if($affect === false){
                throw new Exception('删除评论的回复失败，请重试。');
            }
            $count += $affect;
        }


        $this->commentCounter( -$count );

    }

    //递增递减方法
    private function commentCounter($count){
        try{
            Article::updateAllCounters(['comment'=>$count],['id'=>$this->article_id]);
        }catch (Exception $e){
            //记录日志
            Yii::error($e->getMessage(), __CLASS__);
        }
    }


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%comment}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id','article_id','content'], 'required'],
            [['parent_id', 'article_id'], 'integer'],
            [['content'], 'string', 'max' => 512],

            [['parent_id'], 'exist', 'targetAttribute'  => 'id', 'when'=>function($model){ return $model->parent_id > 0; }],
            [['article_id'], 'exist', 'targetClass' => Article::class, 'targetAttribute'  => 'id'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'content' => '评论内容',
            'article_id' => '评论文章',
            'parent_id' => '回复',
            'user_id' => '用户',
            'created_at' => '评论时间',
        ];
    }

    //关联用户
    public function getUser(){
        return $this->hasOne(User::class, ['id' => 'user_id'])->select(['id','username', 'nickname', 'image']);
    }
    //关联回复
    public function getReplys(){
        return $this->hasMany(static::class, ['parent_id'=>'id'])
            ->select(['id', 'article_id', 'parent_id', 'user_id', 'content','created_at']);
    }

    /**
     * 获取评论列表
     * @param $article_id
     */
    public static function getComments($article_id, $page, $limit){

        $query = self::find()->where(['article_id' => $article_id]);
        $number = $query->count();

        $count = $query->andWhere(['parent_id'  => 0])->count();
        $pagination = new Pagination(['totalCount' => $count]);
        $pagination->pageParam = 'page';
        $pagination->pageSizeParam = 'limit';

        $pagination->setPage($page-1);
        $pagination->setPageSize($limit);

        $comments =  $query
            ->select(['id', 'article_id', 'parent_id', 'user_id', 'content','created_at'])
            ->with('user', 'replys', 'replys.user')
            ->orderBy(['created_at'=>SORT_DESC])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        //排序
        static::processData($comments);

        return [
            'count' => $number,
            'comments' => $comments ? $comments : null,
            'pagination' => static::getPager($pagination),
        ];
    }

    /**
     * 递归排序评论内容
     */
    public static function processData(&$comments){

        foreach( $comments as &$comment ){
            $comment['content'] = Html::encode($comment['content']);
            $comment['created_at'] = ViewHelper::time($comment['created_at']);
            $comment['user']['image'] = ViewHelper::avatar($comment['user']['image']);
            $comment['user']['username'] = ViewHelper::username($comment['user']['username'],$comment['user']['nickname']);
            $comment['owner'] = (!Yii::$app->user->isGuest) && ($comment['user']['id'] === Yii::$app->user->id);
            if(!empty($comment['replys'])){
                static::processData($comment['replys']);
            }
        }
    }

    /**
     * 获取评论页码列表
     */
    public static function getPager(Pagination $pagination){
        try{
            return LinkPager::widget([
                'pagination' => $pagination,
                'options'=>[
                    'tag'=>'nav',
                    'class' => 'pagination',
                    'id' => 'pagination',
                ],
                'linkOptions' =>[
                    'class' => 'pagination__page',
                ],
                'linkContainerOptions' => [
                    'tag'=>false
                ],
                'disabledListItemSubTagOptions' => [
                    'tag'=>'a',
                    'class'=>'pagination__page pagination__page--current',
                    'href'=>'javascript:void(0)'
                ],
                'disableCurrentPageButton' => true,
                'nextPageLabel' => '<i class="ui-arrow-right"></i>',
                'prevPageLabel' => '<i class="ui-arrow-left"></i>',
            ]);
        }catch (\Exception $e){
            return '';
        }

    }

    /**
     * 删除评论
     * @return bool|false|int 影响的行数 或false
     * @throws \yii\db\Exception
     */
    /*public function deleteComment(){
        $transaction = static::getDb()->beginTransaction();
        try{
            $affcted = static::deleteAll(['comment_id' => $this->id]);
            $deleted = $this->delete();
            if ( ($affcted === false) || ($deleted === false) ){
                throw new Exception('删除评论失败，请重试。');
            }
            $transaction->commit();
            return $affcted + $deleted;
        }catch (\Exception $e){
            $transaction->rollBack();
            return false;
        }catch (\Throwable $e){
            $transaction->rollBack();
            return false;
        }
    }*/
}
