<?php

namespace app\models\content;

use app\components\Helper;
use app\models\member\User;
use Yii;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "{{%topic}}".
 *
 * @property string $id 主键
 * @property string $name 话题名称
 * @property string $image 话题封面
 * @property string $desc 话题描述
 * @property string $count 收录文章
 * @property int $status 话题状态: 1 正常,2 完结,3 冻结
 * @property int $check 审核状态: 1 待审核,2 审核通过,3 审核失败
 * @property int $secrecy 私有话题: 1 私有,2 公开
 * @property string $user_id 创建者
 * @property string $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class Topic extends \yii\db\ActiveRecord
{
    const STATUS_NORMAL  =  1; //连载
    const STATUS_FINISH  =  2; //完结
    const STATUS_RECYCLE =  3; //删除

    //审核状态
    const CHECK_WAIT = 1;
    const CHECK_ADOPT = 2;
    const CHECK_DENIAL = 3;

    //公开状态
    const SECR_PRIVATE = 1;
    const SECR_PUBLIC = 2;

    const SCENARIO_STATUS = 'status';

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
            ],
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'user_id',
                'updatedByAttribute' => null,
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%topic}}';
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //设置状态
            [['status', 'check'], 'required', 'on'=>[self::SCENARIO_STATUS]],

            [['status', 'check'], 'in', 'range' => [1,2,3]],

            //crud
            [['secrecy', 'name', 'image','desc', 'category_id'], 'required'],

            [['category_id'], 'exist', 'targetClass' => Category::class, 'targetAttribute' => 'id'],

            [['secrecy'], 'in', 'range' => [1, 2]],

            [['name'], 'string', 'max' => 18],

            //每个用户不能在统一话题建立同名话题（回收站中的除外）
            [['name'], 'uniqueOnUser'],

            [['image'], 'string', 'max' => 125],

            [['desc'], 'string', 'max' => 225],
        ];
    }

    public function uniqueOnUser($attr){
        if($this->hasErrors())
            return false;

        //检测当前用户是否创建过同名话题
        $query = self::find()->where([
            'user_id' => Yii::$app->user->getId(),
            'name' => $this->name,
            'category_id' => $this->category_id,
        ])->andWhere(['!=', 'status', self::STATUS_RECYCLE]); //排除回收站的话题

        if(!$this->isNewRecord){
            $query->andWhere(['!=', 'id', $this->id]);
        }

        $sum = $query->count();

        if($sum){
            $this->addError($attr, '您已经创建了该话题了，不能再次创建。');
            return false;
        }
        return true;
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_STATUS] = ['status', 'check'];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'name' => '话题名称',
            'image' => '话题封面',
            'desc' => '话题描述',
            'count' => '收录文章',
            'status' => '话题状态',     //: 1 正常,2 完结,3 冻结',
            'check' => '审核状态',      //: 1 待审核,2 审核通过,3 审核失败',
            'secrecy' => '展示状态',    //: 1 私有,2 公开',
            'user_id' => '创建者',
            'category_id' => '所属分类',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }


    /**
     * #根据话题id获取简要信息[id=>1, name=>'text']
     */
    public static function getSimpleData($topic_id){
        return self::find()
            ->select(['id','name'])
            ->where(['id'=>$topic_id])
            ->asArray()
            ->one();
    }

    /**
     * 检测当前话题下包含文章数目
     * @return int #包含文章数目
     */
    public function hasArticlesCount(){
        //检测是否包含文章
        return Article::find()->where(['topic_id'=>$this->id])->count();
    }

    /**
     * 检测当前话题下包含的公示文章数目
     * @return int #包含文章数目
     */
    public function hasPublishArticlesCount(){
        //检测是否包含文章
        return Article::find()->where([
            'and',
            ['topic_id'=>$this->id],
            ['!=', 'status', Article::STATUS_RECYCLE]
        ])->count();
    }

    /**
     * 关联标签
     */
    public function getTags(){
        return $this->hasMany(Tag::class, ['topic_id' => 'id'])
            ->select(['id', 'topic_id', 'name']);
    }

    /**
     * 关联分类
     */
    public function getCategory(){
        return $this->hasOne(Category::class, ['id'=>'category_id'])
            ->select(['id','name','user_id']);

    }

    /**
     * 关联用户
     */
    public function getUser(){
        return $this->hasOne(User::class, ['id'=>'user_id'])
            ->select(['id','username', 'nickname']);

    }

    /**
     * 关联文章
     */
    public function getArticles(){
        return $this->hasMany(Article::class, ['topic_id'=>'id']);
    }

    /**
     * 创建话题
     * @return bool
     */
    public function store(){


        if( !$this->validate() ){
            return false;

        }
        return $this->save(false);


    }

    /**
     * 修改话题
     * @return bool
     */
    public function modify(){

        //验证数据
        if(!$this->validate()){
            return false;
        }



        //查看是否有修改图片
        if($this->getDirtyAttributes(['image']) && $this->getOldAttribute('image')){
            //1.删除原有图片
            Helper::delImage($this->getOldAttribute('image'));
        }

        return $this->save(false);

    }

    /**
     * 彻底删除话题
     */
    public function discard(){
        $transaction = self::getDb()->beginTransaction();
        try{
            //检测是否包含文章
            if($this->hasArticlesCount()){
                throw new Exception('请先彻底删除该话题下所有的文章。');
            }

            //删除当前话题下所有的标签数据
            //1. 获取当前话题下所有标签的id
            //$tag_ids = array_keys(Tag::getTagsByTopic($this->id));
            //2. 删除标签
            if(Tag::deleteAll(['topic_id'=>$this->id]) === false){
                throw new Exception('删除话题中的标签失败,请重试。');
            }

            //删除图片
            Helper::delImage($this->image);

            //删除话题自身
            if( !$this->delete() ){
                throw new Exception('删除话题失败,请重试。');
            }

            $transaction->commit();
            return true;

        }catch(Exception $e){
            $transaction->rollBack();
            throw $e;
        }

    }

    /**
     * 删除话题至回收站
     */
    public function del(){
        try{
            //检测是否包含公示文章
            if($this->hasPublishArticlesCount()){
                throw new Exception('请先删除该话题下所有的文章。');
            }

            if($this->updateAttributes(['status'=>Topic::STATUS_RECYCLE]) === false){
                throw new Exception('删除话题失败,请重试。');
            }

            return true;

        }catch(Exception $e){
            throw $e;
        }

    }


    /*home*/
    //根据话题id获取话题简要信息 id 名称 描述
    public static function findOneOfSimple($id){
        return self::find()
            ->select(['id','name','desc'])
            ->where(['id'=>$id])
            ->one();
    }

    //根据话题id获取文章列表数据 及 分页
    public static function getArticlesByTopic($id){
        $query = Article::find()
            ->where(['topic_id' => $id])
            ->with(['topic','user']);

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                    'id' => SORT_DESC,
                ]
            ],
        ]);

        return $provider;
    }
    //获取指定作者N个活跃话题
    public static function getActiveTopicsByUser($user_id, $limit){
        if($user_id <= 0) return [];
        $ret = self::find()
            ->with(['category'])
            ->where([
                'check' => self::CHECK_ADOPT,
                'secrecy' => self::SECR_PUBLIC
            ])
            ->andWhere(['!=', 'status', self::STATUS_RECYCLE])
            ->andWhere(['user_id'=>$user_id])
            ->limit($limit)
            ->orderBy(['updated_at'=>SORT_DESC])
            ->asArray()
            ->all();
        return $ret;

    }

    public static function getSecrecyTopicByUser($user_id){
        if($user_id <= 0) return [];
        $ret = self::find()
            ->with(['category'])
            ->where([
                'secrecy' => self::SECR_PRIVATE
            ])
            ->andWhere(['!=', 'status', self::STATUS_RECYCLE])
            ->andWhere(['user_id'=>$user_id])
            ->orderBy(['updated_at'=>SORT_DESC])
            ->asArray()
            ->all();
        return $ret;
    }


    /**
     * 根据用户获取话题总数(去除回收站 私有 非审核通过的话题)
     * @param $user_id
     */
    public static function getCountByUser($user_id){
        if(!$user_id)
            return -1;

        return self::find()->where([
            'check' => self::CHECK_ADOPT,
            'secrecy' => self::SECR_PUBLIC,
            'user_id' => $user_id
        ])->andWhere([
            '!=',
            'status',
            self::STATUS_RECYCLE
        ])->count();
    }


}
