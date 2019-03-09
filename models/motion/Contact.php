<?php

namespace app\models\motion;

use app\models\member\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "{{%contact}}".
 *
 * @property string $id 主键
 * @property string $email 联系邮箱
 * @property string $subject 主题
 * @property string $message 消息主体
 * @property string $user_id 用户
 * @property string $created_at 创建时间
 */
class Contact extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%contact}}';
    }

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
                'updatedByAttribute' =>  null
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'subject','message'], 'required'],
            [['email'], 'email'],
            [['email'], 'string', 'max' => 64],
            [['subject'], 'string', 'max' => 125],
            [['message'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'email' => '联系邮箱',
            'subject' => '主题',
            'message' => '消息主体',
            'user_id' => '用户',
            'created_at' => '创建时间',
        ];
    }

    public function getUser(){
        return $this->hasOne(User::class, ['id'=>'user_id'])
            ->select(['id','username','nickname']);
    }



}
