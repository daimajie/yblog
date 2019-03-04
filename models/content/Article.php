<?php

namespace app\models\content;

use app\components\events\ArticlePutEvent;
use app\components\Helper;
use app\models\member\User;
use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

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
    const EVENT_AFTER_CHECK =  'check'; //恢复事件 restore
    const EVENT_AFTER_BATCH = 'batch'; //批量操作

    //场景
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
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'category_id',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'category_id',
                ],
                'value' => function ($event) {
                    return Topic::find()->where(['id'=>$this->topic_id])->select(['category_id'])->scalar();
                },
            ],
        ];
    }

    //绑定事件
    public function init()
    {
        parent::init();

        //$this->on(self::EVENT_AFTER_ADD, [$this, 'articleHandler'], 'add');
        $this->on(self::EVENT_AFTER_REC, [$this, 'articleHandler'], 'rec');
        $this->on(self::EVENT_AFTER_RES, [$this, 'articleHandler'], 'res');

        $this->on(self::EVENT_AFTER_PUT, [$this, 'articlePutHandler']);
        $this->on(self::EVENT_AFTER_CHECK, [$this, 'articleCheckHandler']);
    }

    public function articleHandler($event){

        try{
            if(!($event instanceof ArticlePutEvent)){
                throw new Exception('事件参数错误。');
            }

            $operate = $event->data;
            $topic_id = $event->topic_id;
            $user_id = $event->user_id;

            $check = $event->check;
            $status = $event->status;
            $oldStatus = $event->oldStatus;


            //非审核通过的文章 和 非公示文章 不做任何操作
            if($check != self::CHECK_ADOPT){
                return;
            }
            //草稿箱
            if($status == self::STATUS_DRAFT || $oldStatus == self::STATUS_DRAFT){
                return;
            }
            //非放置回收站 或 恢复文章 不做任何事
            if($status != self::STATUS_NORMAL && $oldStatus != self::STATUS_NORMAL){
                return;
            }


            /**以下是单一模型操作 (还有批量操作) **/
            //create
            /*if($operate === 'add'){
                Topic::updateAllCounters(['count'=>1],['id'=>$topic_id]);

                User::updateAllCounters(['author'=>1],['id'=>$user_id]);
            }*/
            //delete
            if($operate === 'rec'){
                Topic::updateAllCounters(['count'=>-1],['id'=>$topic_id]);

                User::updateAllCounters(['author'=>-1],['id'=>$user_id]);
            }
            //restore
            if($operate === 'res'){
                Topic::updateAllCounters(['count'=>1],['id'=>$topic_id]);

                User::updateAllCounters(['author'=>1],['id'=>$user_id]);
            }
        }catch (Exception $e){
            //记录日志
            //Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }



    }

    /**
     * #修改文章事件
     * #重新发布 要重新审核 统计数要减一
     * @param $event
     */
    public function articlePutHandler($event){
        try{
            if(!($event instanceof ArticlePutEvent)){
                throw new Exception('事件参数错误。');
            }

            //1.判断文章编辑之前是否被收录过
            $status = isset($event->oldStatus) ? $event->oldStatus:$event->status;
            $check = isset($event->oldCheck) ? $event->oldCheck:$event->check;

            $topic_id = isset($event->oldTopic_id)?$event->oldTopic_id : $event->topic_id;
            $user_id = $event->user_id;


            if($status == self::STATUS_NORMAL && $check == self::CHECK_ADOPT){
                //2.判断文章话题是否更改(没改-话题收录减一，改了-原来话题收录减一)
                Topic::updateAllCounters(['count'=>-1],['id'=>$topic_id]);

                User::updateAllCounters(['author'=>-1],['id'=>$user_id]);
            }
        }catch (Exception $e){
            //记录日志
            //Yii::error($e->getMessage(), __METHOD__);
            throw $e;

        }


    }

    /**
     * 修改文章审核状态时 改变话题收录数目
     * @param $event
     */
    public function articleCheckHandler($event){
        try{
            if(!($event instanceof ArticlePutEvent)){
                return;
            }

            //获取新旧状态值
            $status = $event->status;
            $check = $event->check;
            $oldCheck = isset($event->oldCheck) ? $event->oldCheck: '';
            $topic_id = $event->topic_id;
            $user_id = $event->user_id;

            //如果审核状态没有改变 或是不是公示文章 不做任务计数操作
            if(empty($oldCheck) || $status != self::STATUS_NORMAL)
                return;


            //1.如果由待审核转为审核通过
            if($check == self::CHECK_ADOPT && $oldCheck < $check){
                Topic::updateAllCounters(['count'=>1],['id'=>$topic_id]);

                User::updateAllCounters(['author'=>1],['id'=>$user_id]);
            }

            //2.如果由审核通过转为待审核
            if($oldCheck == self::CHECK_ADOPT && $oldCheck > $check){
                Topic::updateAllCounters(['count'=>-1],['id'=>$topic_id]);

                User::updateAllCounters(['author'=>-1],['id'=>$user_id]);
            }

            //3.如果由什么通过转为审核失败
            if($oldCheck == self::CHECK_ADOPT && $oldCheck < $check){
                Topic::updateAllCounters(['count'=>-1],['id'=>$topic_id]);

                User::updateAllCounters(['author'=>-1],['id'=>$user_id]);
            }

            //4.如果由什么失败转为什么通过
            if($check == self::CHECK_ADOPT && $oldCheck > $check){
                Topic::updateAllCounters(['count'=>1],['id'=>$topic_id]);

                User::updateAllCounters(['author'=>1],['id'=>$user_id]);
            }
        }catch (Exception $e){
            //记录日志
            //Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }


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
            [[/*'status', */'check'], 'required', 'on'=>self::SCENARIO_STATUS],

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
        $scenarios[self::SCENARIO_STATUS] = [/*'status', */'check'];
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
            'category_id' => '所属分类',
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
        return $this->hasOne(Topic::class, ['id' => 'topic_id'])
            ->select(['id','name','status','check','secrecy']);
    }

    public function getUser(){
        return $this->hasOne(User::class, ['id'=>'user_id'])
            ->select(['id','username','nickname', 'image']);
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

    public static function getCounter($ids){
        $counter = self::find()
            ->where([
                'check' => self::CHECK_ADOPT, //审核通过的
                'status' => self::STATUS_NORMAL, //公示文章
                'id' => $ids
            ])
            ->select(['topic_id','user_id'])
            ->asArray()
            ->all();

        $topic_c = ArrayHelper::getColumn($counter,'topic_id');
        $user_c = ArrayHelper::getColumn($counter, 'user_id');

        return [
            'user_count' => array_count_values($user_c),
            'topic_count' => array_count_values($topic_c)
        ];


    }


    /**
     * #再批量操作文章的时候 获取每个话题统计的操作数目
     * #如 话题1 里两个文章要删除,话题2删除1篇文章 将得到 [1 => 2, 2 => 1]
     * #如上操作的统计数目 必须是 公示审核通过的文章(因为其他的文章不在统计数目里面)
     * @param $ids
     * @return array
     */
    public static function getCountNum(array $ids){
        $counter = self::find()
            ->where([
                'check' => self::CHECK_ADOPT, //审核通过的
                'status' => self::STATUS_NORMAL, //公示文章
                'id' => $ids
            ])
            ->select(['topic_id','user_id'])
            ->asArray()
            ->all();

        $topic_c = ArrayHelper::getColumn($counter,'topic_id');
        $user_c = ArrayHelper::getColumn($counter, 'user_id');

        return [
            'user_count' => array_count_values($user_c),
            'topic_count' => array_count_values($topic_c)
        ];
    }

    /**
     * #获取要恢复文章的统计数 只计算审核通过并且不是公示文章的数据(因为非公示非审核不在统计数目之中)
     * @param array $ids
     * @return array
     */
    public static function getRestoreCountNum(array $ids){
        $counter = self::find()
            ->where([
                'check' => self::CHECK_ADOPT, //审核通过的
                //'status' => self::STATUS_NORMAL, //公示文章
                'id' => $ids
            ])
            ->andWhere(['!=', 'status', self::STATUS_NORMAL])
            ->select(['topic_id','user_id'])
            ->asArray()
            ->all();

        $topic_c = ArrayHelper::getColumn($counter,'topic_id');
        $user_c = ArrayHelper::getColumn($counter, 'user_id');

        return [
            'user_count' => array_count_values($user_c),
            'topic_count' => array_count_values($topic_c)
        ];
    }

    /**
     * #当批量审核通过时 获取每个话题应该添加的计数
     * @param $ids
     * @return array
     */
    public static function getCheckCountNum($ids){
        $counter = self::find()
            ->where([
                'check' => self::CHECK_WAIT, //等待审核的
                'status' => self::STATUS_NORMAL, //公示文章
                'id' => $ids
            ])
            ->select(['topic_id','user_id'])
            ->asArray()
            ->all();

        $topic_c = ArrayHelper::getColumn($counter,'topic_id');
        $user_c = ArrayHelper::getColumn($counter, 'user_id');

        return [
            'user_count' => array_count_values($user_c),
            'topic_count' => array_count_values($topic_c)
        ];
    }


    /**
     * 批量操作时 对于话题收录数的操作
     */
    public static function batchOperateCount($count, $sign='inc'){
        if(empty($count['user_count']) || empty($count['topic_count']))
            return;

        try{
            foreach ($count['topic_count'] as $k => $v){
                //$k为要改变的话题id  $v为要加减的数目
                $v = $sign == 'inc' ? $v : -$v;

                Topic::updateAllCounters(['count' => $v],['id'=>$k]);
            }

            foreach ($count['user_count'] as $k => $v){
                //$k为要改变的话题id  $v为要加减的数目
                $v = $sign == 'inc' ? $v : -$v;

                User::updateAllCounters(['author' => $v],['id'=>$k]);
            }

        }catch (Exception $e){
            //记录日志
            //Yii::error($e->getMessage(), __METHOD__);
            throw $e;
        }

    }



    /*home*/
    /**
     * 获取指定文章模型
     * @param $id
     * @return Article|array|ActiveRecord|null
     */
    public static function singleArticle($id){

        return self::find()->with(['topic','user','tags','content'])
            ->where([
                'id' => $id,
                'status' => self::STATUS_NORMAL,
                'check' => self::CHECK_ADOPT
            ])
            ->asArray()
            ->one();
    }

    /**
     * 获取查询生成器
     * @return \yii\db\ActiveQuery
     */
    public static function getArticleQuery($topic_id){
        return self::find()
            ->where([
                'status' => self::STATUS_NORMAL, //公示文章
                'check'  => self::CHECK_ADOPT,  //审核通过
                'topic_id' => $topic_id         //同一话题下
            ]);
    }

    /**
     * 上一页下一页
     */
    public static function getPrevNext($article_id, $topic_id){
        if(!is_numeric($article_id) || !is_numeric($topic_id))
            return [];

        $prev = static::getArticleQuery($topic_id)
            ->orderBy(['created_at'=>SORT_ASC])
            ->andWhere(['>', 'id', $article_id])
            ->select(['id','title'])
            ->asArray()
            ->one();
        $next = static::getArticleQuery($topic_id)
            ->orderBy(['created_at'=>SORT_DESC])
            ->andWhere(['<', 'id', $article_id])
            ->select(['id', 'title'])
            ->asArray()
            ->one();
        return ['prev' => $prev, 'next' => $next];
    }

}
