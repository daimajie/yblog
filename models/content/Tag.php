<?php

namespace app\models\content;

use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "{{%tag}}".
 *
 * @property string $id 主键
 * @property string $name 标签名称
 * @property string $topic_id 所属话题
 * @property string $user_id 创建者
 * @property string $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class Tag extends \yii\db\ActiveRecord
{
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
        return '{{%tag}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name','topic_id'], 'required'],
            [['topic_id'], 'integer'],
            [['topic_id'], 'exist', 'targetClass' => Topic::class, 'targetAttribute' => ['topic_id' => 'id']],
            [['name'], 'string', 'max' => 8],

            [['name'], 'checkUnique'],
            [['name'], 'checkTop']
        ];
    }

    /**
     * 检测同一个话题下是否有同名标签
     */
    public function checkUnique($attribute){
        if ($this->hasErrors())
            return false;

        //查询同一个话题下是否存在同名标签
        $count = self::find()
            ->where(['topic_id'=>$this->topic_id])
            ->andWhere(['name'=>$this->name])
            ->count();

        if ( $count && !$this->isNewRecord ){
            $this->addError($attribute, '不能使用原名称.');
            return false;
        }


        if ( $count ){
            $this->addError($attribute, '该话题下已存在同名标签.');
            return false;
        }
        return true;
    }

    /**
     * 创建新标签时检测是否已达到上限(一个话题下允许创建若干标签)
     */
    public function checkTop($attr){
        if ($this->hasErrors())
            return false;

        //获取上限
        $limit = Yii::$app->params['tag']['limit'];

        //获取当前话题下标签数目
        $count = self::find()
            ->where(['topic_id' => $this->topic_id])
            ->count();

        if($limit <= $count){
            $this->addError($attr, '当前话题包含标签已达上限。');
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'name' => '标签名称',
            'topic_id' => '所属话题',
            'user_id' => '创建者',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * 关联话题
     */
    public function getTopic(){
        return $this->hasOne(Topic::class, ['id' => 'topic_id']);
    }

    /**
     * 关联文章
     */
    public function getArticleTag()
    {
        return $this->hasOne(ArticleTag::class, ['tag_id' => 'id']);
    }
    public function getArticle()
    {
        return $this->hasOne(Article::class, ['id' => 'article_id'])
            ->via('articleTag');
    }

    /**
     * #根据话题获取标签
     * @return array #[id => name, id2 => name2]
     */
    public static function getTagsByTopic($topic_id){
        if(intval($topic_id) <= 0)
            throw new Exception('传递参数错误.');

        $tags = self::find()
            ->select(['name'])
            ->indexBy('id')
            ->where([
                'topic_id'=>$topic_id,
            ])
            ->asArray()
            ->column();

        return $tags;
    }

    /**
     * 检测制定话题下还可以建立多少个标签
     * @params $topic_id int #话题id
     */
    public static function checkLimit($topic_id){
        $topic_id = intval($topic_id);
        if ($topic_id <= 0)
            throw new Exception('传递话题id错误');

        //获取标签上限
        $limit = (int) Yii::$app->params['tag']['limit'];
        if($limit <= 0){
            throw new InvalidConfigException('tag limit config error');
        }

        $count = self::find()
            ->where(['topic_id'=>$topic_id])
            ->count();

        //返回该话题还能创建的标签个数
        if($limit <= $count)
            return 0;

        return ($limit - $count);
    }

    /**
     * 删除标签
     */
    public function discard(){
        $transaction = self::getDb()->beginTransaction();
        try {
            //删除标签关联信息
            if(ArticleTag::deleteAll(['tag_id'=>$this->id]) === false){
                throw new Exception('删除标签关联失败，请重试。');
            }

            //删除标签自身
            if(!$this->delete()){
                throw new Exception('删除标签失败，请重试。');
            }

            $transaction->commit();
            return true;
        } catch(\Exception $e) {
            $transaction->rollBack();
            //Yii::error($e->getMessage(), __METHOD__);
            throw $e;

        } catch(\Throwable $e) {
            $transaction->rollBack();
            //Yii::error($e->getMessage(), __METHOD__);
            throw $e;

        }
        return false;

    }

}
