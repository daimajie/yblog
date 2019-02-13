<?php

namespace app\modules\admin\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "{{%category}}".
 *
 * @property string $id 主键
 * @property string $name 分类名称
 * @property string $desc 分类描述
 * @property string $user_id 创建者
 * @property string $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class Category extends \yii\db\ActiveRecord
{
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
        return '{{%category}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name','desc'], 'required'],
            [['user_id', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'unique'],
            [['name'], 'string', 'max' => 12],
            [['desc'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'name' => '分类名称',
            'desc' => '分类描述',
            'user_id' => '创建者',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * 检测当前分类下是否包含话题
     */
    public function hasTopics(){
        return Topic::find()->where([
            'category_id' => $this->id,
        ])->count();
    }

    /**
     * 获取分类列表的下拉数据
     */
    public static function dropItems(){
        return Category::find()
            ->select(['name'])
            ->indexBy('id')
            ->orderBy(['id'=>SORT_DESC])
            ->column();
    }

    /**
     * 删除
     */
    public function del(){
        //检测是否包含话题
        if($this->hasTopics()){
           $this->addError('name', '请先清空该分类下所有的话题。');
           return false;
        }

        return $this->delete();
    }
}
