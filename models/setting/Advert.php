<?php

namespace app\models\setting;

use Yii;

/**
 * This is the model class for table "{{%advert}}".
 *
 * @property string $id 主键
 * @property int $switch 广告开关
 * @property string $advert_bar 页头广告
 * @property string $advert 侧边栏广告
 * @property string $updated_at 修改时间
 */
class Advert extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%advert}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['switch'], 'in', 'range' => [0,1]], //0是关闭 1为开启
            [['advert_bar', 'advert'], 'string'],
            [['advert', 'advert_bar'], 'required', 'when' => function(){
                    return (int)$this->switch === 1; //开启广告时 内容不能为空
            }]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'switch' => '广告开关',
            'advert_bar' => '页头广告( 源码 )',
            'advert' => '侧边栏广告( 源码 )',
            'updated_at' => '修改时间',
        ];
    }
}
