<?php

namespace app\models\member;

use Yii;

/**
 * This is the model class for table "{{%profile}}".
 *
 * @property string $id 主键
 * @property string $address 地址
 * @property string $intro 介绍
 * @property string $blog 博客地址
 * @property string $photo 生活照
 * @property string $qrcode 打赏二维码
 * @property string $contact 社交账号
 * @property string $user_id 所属用户
 *
 * @property User $user
 */
class Profile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%profile}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['address', 'blog'], 'string', 'max' => 64],
            [['intro', 'photo', 'qrcode'], 'string', 'max' => 125],
            [['contact'], 'string', 'max' => 512],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'address' => '所在地',
            'intro' => '介绍',
            'blog' => '博客地址',

            'photo' => '生活照',
            'qrcode' => '打赏二维码',

            //'contact' => '社交账号',
            //'user_id' => '所属用户',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }


}
