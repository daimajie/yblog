<?php

namespace app\models\content;

use app\components\Helper;
use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "{{%article}}".
 *
 * @property string $id 主键
 * @property string $title 文章标题
 * @property string $brief 文章简介
 * @property string $image 文章图片
 * @property int $status 文章状态: 1正常, 2草稿, 3回收站
 * @property int $check 审核状态: 1待审核, 2通过, 3审核失败
 * @property string $visited 访问次数
 * @property string $comment 评论数
 * @property string $topic_id 所属话题
 * @property string $user_id 作者
 * @property string $content 文章id
 * @property string $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class Article extends ArticleForm
{
    //文章状态
    const STATUS_NORMAL = 1;
    const STATUS_DRAFT = 2;
    const STATUS_RECYCLE = 3;

    //审核状态
    const CHECK_WAIT = 1;
    const CHECK_ADOPT = 2;
    const CHECK_DENIAL = 3;

    //自定义事件 (写文章后 放置回收站后 进行话题文章计数)
    const EVENT_AFTER_ADD =  'add'; //添加事件 create
    const EVENT_AFTER_REC =  'rec'; //放置回收站事件 delete
    const EVENT_AFTER_PUT =  'put'; //修改事件 update
    const EVENT_AFTER_RES =  'res'; //恢复事件 restore


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

    //绑定事件
    public function init()
    {
        parent::init();

        $this->on(self::EVENT_AFTER_ADD, [$this, 'articleHandler'], 'add');
        $this->on(self::EVENT_AFTER_REC, [$this, 'articleHandler'], 'rec');
        //$this->on(self::EVENT_AFTER_PUT, [$this, 'articleHandler'], 'put');
        $this->on(self::EVENT_AFTER_RES, [$this, 'articleHandler'], 'res');

        $this->on(self::EVENT_AFTER_PUT, [$this, 'articlePutHandler']);
    }

    public function articleHandler($event){

        try{
            $model = $event->sender;
            if(!($model instanceof ActiveRecord)){
                throw new Exception('事件参数错误。');
            }

            //非审核通过的文章 和 非公示文章 不做任何操作
            if($model->check != self::CHECK_ADOPT || $model->status != self::STATUS_NORMAL){
                return;
            }

            $topic_id = $event->sender->topic_id;
            $operate = $event->data;

            //验证数据
            if($topic_id <= 0 || empty($operate)){
                throw new Exception('事件参数错误。');
            }

            /**以下是单一模型操作 (还有批量操作) **/
            //create
            if($operate === 'add'){
                Topic::updateAllCounters(['count'=>1],['id'=>$topic_id]);
            }
            //delete
            if($operate === 'rec'){
                Topic::updateAllCounters(['count'=>-1],['id'=>$topic_id]);
            }
            //update
            /*if($operate === 'put' && $model->getDirtyAttributes(['topic_id'])){
                Topic::updateAllCounters(['count'=>-1],['id'=>$model->getOldAttribute('topic_id')]);
                //Topic::updateAllCounters(['count'=>1],['id'=>$topic_id]);
            }
            if($operate === 'put' && !$model->getDirtyAttributes(['topic_id'])){
                //Topic::updateAllCounters(['count'=>-1],['id'=>$model->getOldAttribute('topic_id')]);
                Topic::updateAllCounters(['count'=>1],['id'=>$topic_id]);
            }*/
            //restore
            if($operate === 'res'){
                Topic::updateAllCounters(['count'=>1],['id'=>$topic_id]);
            }
        }catch (Exception $e){
            //记录日志
            Yii::error($e->getMessage(), __METHOD__);
            //throw $e;
        }



    }

    public function articlePutHandler($event){

    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%article}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(parent::rules(),[
            [['status', 'check'], 'required', 'on'=>self::SCENARIO_STATUS],

            [['title', 'brief', 'topic_id'], 'required'],

            [['topic_id'], 'exist', 'targetClass' => Topic::class, 'targetAttribute' => ['topic_id'=>'id']],

            [['title'], 'string', 'max' => 75],
            [['brief'], 'string', 'max' => 225],
            [['image'], 'string', 'max' => 125],

            [['status', 'check'], 'in', 'range' => [1,2,3], 'message' => '请正确选择文章状态信息'],
            [['status', 'check'], 'default', 'value' => 1],
        ]);
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
        return array_merge(parent::attributeLabels(),[
            'id' => '主键',
            'title' => '文章标题',
            'brief' => '文章简介',
            'image' => '文章图片',
            'status' => '文章状态',
            'check' => '审核状态',
            'visited' => '访问次数',
            'comment' => '评论数',
            'topic_id' => '所属话题',
            'user_id' => '作者',
            'content_id' => '文章id',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
            'content' => '文章内容',
            'tags' => '可用标签'
        ]);
    }

    /**
     * 关联话题
     */
    public function getTopic(){
        return $this->hasOne(Topic::class, ['id' => 'topic_id']);
    }

    /**
     * 关联内容
     */
    public function getContent(){
        return $this->hasOne(Content::class, ['id' => 'content_id']);
    }
    public function getRelationContent(){
        $this->art_content = $this->content->content;
    }

    /**
     * 关联标签
     */
    public function getArticleTag()
    {
        return $this->hasMany(ArticleTag::class, ['article_id' => 'id']);
    }
    public function getTags()
    {
        return $this->hasMany(Tag::class, ['id' => 'tag_id'])
            ->via('articleTag');
    }
    //返回当前文章关联的所有标签 [id,id2,id3]
    public function getRelationTagsId(){
        $arr = $this->getTags()->select(['id'])->asArray()->all();
        $this->arr_tags = array_column($arr, 'id');
    }
    //返回当前文章关联的所有标签 [id=>name,id2=>name2]
    public function getRelationTags(){
        return $this->getTags()->select(['name'])->indexBy('id')->asArray()->column();
    }





    /**
     * 创建文章
     */
    public function store(){
        //验证数据
        if(!$this->validate())
            return false;

        //创建事务
        $transaction = self::getDb()->beginTransaction();
        try{

            //1.插入文章内容
            $this->saveContent();

            //2.保存文章
            $this->saveArticle();

            //3.建立标签
            $this->saveTags();


            $transaction->commit();
            return true;

        }catch(InvalidConfigException $e){
            $transaction->rollBack();
            throw $e; //配置错误直接抛


        }catch(\Exception $e){
            $transaction->rollBack();
            //记录日志
            Yii::error($e->getMessage(), __METHOD__);
            //throw $e;

        }catch(\Throwable $e){
            $transaction->rollBack();
            //记录日志
            Yii::error($e->getMessage(), __METHOD__);
            //throw $e;
        }

        return false;
    }

    /**
     * 修改文章
     * @return bool
     * @throws InvalidConfigException
     * @throws \Throwable
     * @throws \yii\db\Exception
     */
    public function modify(){
        //验证数据
        if(!$this->validate())
            return false;

        //创建事务
        $transaction = self::getDb()->beginTransaction();
        try{

            //1.插入文章内容
            $this->modifyContent();

            //2.保存文章
            $this->saveArticle();

            //3.清空该文章所有关联标签
            $this->clearRelationTag();

            //3.建立标签
            $this->saveTags();


            $transaction->commit();
            return true;

        }catch(InvalidConfigException $e){
            $transaction->rollBack();
            throw $e; //配置错误直接抛


        }catch(\Exception $e){
            $transaction->rollBack();
            //记录日志
            //Yii::error($e->getMessage(), __METHOD__);
            throw $e;

        }catch(\Throwable $e){
            $transaction->rollBack();
            //记录日志
            //Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }

        return false;
    }

    /**
     * 删除文章
     */
    public function discard(){
        if($this->status !== self::STATUS_RECYCLE){
            $this->addError('status', '该文章不在回收站.');
        }

        $transaction = self::getDb()->beginTransaction();
        try {
            //删除内容
            Content::findOne($this->content_id)->delete();

            //删除标签关联
            ArticleTag::deleteAll(['article_id'=>$this->id]);

            //删除文章
            $this->delete();

            //删除图片
            Helper::delImage($this->image);

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


    /**
     *更新文章内容
     */
    public function modifyContent(){
        $ret = Content::updateAll([
            'words' => mb_strlen($this->art_content),
            'content' => $this->art_content
        ], [
            'id' => $this->content_id
        ]);

        if($ret === false){
            $this->addError('art_content', '更新文章内容失败，请重试。');
            throw new Exception('更新文章内容失败：' . Json::encode($this->getErrorSummary(true)));
        }

        return $ret;
    }

    /**
     * #删除当前文章所有关联标签数据
     */
    public function clearRelationTag(){
        $ret = ArticleTag::deleteAll(['article_id'=>$this->id]);
        if($ret === false){
            $this->addError('arr_tags', '更新标签失败');
            throw new Exception('更新标签失败：' . Json::encode($this->getErrorSummary(true)));
        }

        return $ret;
    }

    /**
     * #保存文章内容
     * @return bool
     * @throws Exception
     */
    private function saveContent(){
        $model = new Content();
        $model->words = mb_strlen($this->art_content);
        $model->content = $this->art_content;


        if( !$model->save(false) ){
            $this->addError('art_content', '保存文章内容失败，请重试。');
            throw new Exception('保存文章内容失败：' . Json::encode($this->getErrorSummary(true)));
        }

        //填充content_id
        $this->content_id = $model->id;
        return true;
    }


    /**
     * #保存文章数据
     * @return bool
     */
    private function saveArticle(){
        if(!$this->save(false)){
            //$this->addError('title', '保存文章失败，请重试。');
            throw new Exception('保存文章失败：' . Json::encode($this->getErrorSummary(true)));
        }
        return true;
    }


    /**
     * #新建标签 并保存关联数据
     * @param $arr
     * @return bool
     * @throws InvalidConfigException
     */
    private function saveTags(){

        $tags = []; //存放标签id

        //**建立新标签
        try{
            if(!empty($this->new_tags)){
                $new_tags = trim($this->new_tags);
                if(mb_strlen($new_tags) > 0){
                    //创建新标签 返回新建标签id数组
                    $tags = array_merge($tags, ArticleForm::createNewTags($new_tags, $this->topic_id));

                }

            }

        }catch(InvalidConfigException $e){

            throw $e; //配置错误直接抛
        }catch(Exception $e){

            $this->addError('new_tags', '新建标签出错，请重试。');
            throw new Exception($e->getMessage());
        }


        //有选择标签就与新建的标签合并
        if( is_array($this->arr_tags) && count($this->arr_tags) > 0 ){
            $tags = array_merge($tags, $this->arr_tags);
        }


        //**保存关联信息
        if(count($tags) > 0){
            //1.获取文章关联标签的上限数量(限定每篇文章最多可关联几个标签)
            $createLimit =  (int) Yii::$app->params['tag']['createLimit'];
            $articleLimit = (int) Yii::$app->params['tag']['articleLimit'];

            if($createLimit <= 0 || $articleLimit <= 0 || $createLimit > $articleLimit){
                //提示配置错误。
                throw new InvalidConfigException('create tag limit and relation tag limit config error');
            }

            if(count($tags) > $articleLimit){
                $this->addError('tags', '每篇文章可关联最多'. $articleLimit .'个标签。');
                throw new Exception('标签关联错误：' . $this->getErrorSummary(true));
            }

            foreach ($tags as $k => $v){
                $model = new ArticleTag();
                $model->article_id = $this->id;
                $model->tag_id = $v;

                //保存失败
                if(!$model->save()){
                    $this->addError('tags', '保存关联标签错误，请重试。');
                    throw new Exception('标签关联错误：' . $model->getErrorSummary(true));
                }
            }
        }
        return true;
    }








}
