<?php

namespace app\modules\admin\models;

use app\components\Helper;
use Yii;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

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
    const STATUS_NORMAL  =  1; //正常使用
    const STATUS_FINISH  =  2; //完结
    const STATUS_RECYCLE =  3; //删除

    const SCENARIO_STATUS = 'status';

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
            ],
            /*[
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'user_id',
                'updatedByAttribute' => null,
            ]*/
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
            [['secrecy', 'name', 'image','desc'], 'required'],

            [['secrecy'], 'in', 'range' => [1, 2]],

            [['name'], 'string', 'max' => 18],

            [['image'], 'string', 'max' => 125],

            [['desc'], 'string', 'max' => 225],
        ];
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
     * 关联标签
     */
    public function getTags(){
        return $this->hasMany(Tag::class, ['topic_id' => 'id'])
            ->select(['id', 'topic_id', 'name']);
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
        if($this->getDirtyAttributes(['image'])){
            //1.删除原有图片
            Helper::delImage($this->getOldAttribute('image'));
        }

        return $this->save(false);

    }

    /**
     * 删除话题
     */
    public function discard(){
        $transaction = self::getDb()->beginTransaction();
        try{
            //检测是否包含文章
            if($this->hasArticlesCount()){
                throw new Exception('请先删除该话题下所有的文章。');
            }

            //删除当前话题下所有的标签数据
            //1. 获取当前话题下所有标签的id
            $tag_ids = array_keys(Tag::getTagsByTopic($this->id));
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



}
