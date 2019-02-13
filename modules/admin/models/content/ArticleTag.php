<?php

namespace app\modules\admin\models\content;

use Yii;

/**
 * This is the model class for table "{{%article_tag}}".
 *
 * @property string $article_id 文章id
 * @property string $tag_id 标签id
 */
class ArticleTag extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%article_tag}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['article_id', 'tag_id'], 'required'],
            [['article_id', 'tag_id'], 'integer'],
            [['article_id'], 'exist', 'targetClass' => Article::class, 'targetAttribute' => ['article_id' => 'id']],
            [['tag_id'], 'exist', 'targetClass' => Tag::class, 'targetAttribute' => ['tag_id' => 'id']],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'article_id' => '文章id',
            'tag_id' => '标签id',
        ];
    }
}
