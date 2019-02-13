<?php

namespace app\modules\admin\models\content;

use Yii;

/**
 * This is the model class for table "{{%content}}".
 *
 * @property string $id 主键
 * @property string $words 文章字数
 * @property string $content 文章内容
 */
class Content extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%content}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['words','content'], 'required'],
            [['words'], 'integer'],
            [['content'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'words' => '文章字数',
            'content' => '文章内容',
        ];
    }

    /**
     * 关联文章
     */
    public function getArticle(){
        return $this->hasOne(Article::class, ['content_id' => 'id']);
    }
}
