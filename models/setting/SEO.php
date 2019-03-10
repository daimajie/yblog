<?php

namespace app\models\setting;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%seo}}".
 *
 * @property string $id 主键
 * @property string $name 网站名称
 * @property string $keywords 关键字
 * @property string $description 描述
 * @property string $pc_logo PC端LOGO
 * @property string $mobile_logo 移动端LOGO
 * @property string $qrcode 二维码
 * @property string $about 关于我
 */
class SEO extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%seo}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => false,
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name','keywords','description','about'], 'required'],
            [['about'], 'string'],
            [['name'], 'string', 'max' => 7],
            [['pc_logo', 'mobile_logo', 'qrcode'], 'string', 'max' => 125, 'skipOnEmpty' => true],
            [['keywords'], 'string', 'max' => 125],
            [['description'], 'string', 'max' => 225],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'name' => '网站名称',
            'keywords' => '关键字',
            'description' => '描述',
            'pc_logo' => 'PC端LOGO',
            'mobile_logo' => '移动端LOGO',
            'qrcode' => '二维码',
            'about' => '关于我',
            'updated_at' => '修改时间'
        ];
    }
}
