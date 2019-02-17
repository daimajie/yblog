<?php

namespace app\modules\admin\modules\content\controllers;

use app\components\events\ArticlePutEvent;
use app\models\content\Tag;
use app\models\content\Topic;
use app\widgets\select2\actions\SelectAction;
use Yii;
use app\models\content\Article;
use app\models\content\SearchArticle;
use app\modules\admin\controllers\BaseController;
use yii\base\Exception;
use yii\base\UnknownMethodException;
use yii\helpers\VarDumper;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\widgets\upload\actions\UploadAction;
use yii\web\Response;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class ArticleController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'discard' => ['POST'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'select' => [
                'class' => SelectAction::class,
                'table' => '{{%topic}}',
                'index' => 'id',
                'text' => 'name',
                'limit' => '9'
            ],
            'upload' => [
                'class' => UploadAction::class,
                'subDir' => 'article',
                'thumb' => [
                    'width' => 270,
                    'height' => 203
                ]
            ],
        ];
    }



    /**
     * Lists all Article models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchArticle();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Article model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if(Yii::$app->request->isPost){
            $model->scenario = Article::SCENARIO_STATUS;

            if ($model->load(Yii::$app->request->post())) {
                //生成事件数据
                $event = $this->generateEvent($model);


                if( $model->save() ){
                    //触发修改审核状态的事件
                    $model->trigger(Article::EVENT_AFTER_CHECK, $event);


                    //设置状态成功
                    $this->refresh();
                }


            }else{
                //设置状态失败
                //do nothing
            }

        }
        return $this->render('view', [
            'model' => $model,
        ]);
    }


    /**
     * Creates a new Article model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Article();

        if ($model->load(Yii::$app->request->post()) && $model->store()) {
            //触发计数事件
            $model->trigger(Article::EVENT_AFTER_ADD);

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            //保留模型数据
            $topic = [];
            $tags = [];
            if(!empty($model->topic_id)){
                //获取当前话题数据
                $topic = Topic::getSimpleData($model->topic_id);

                //获取当前话题所有可用标签
                $tags = Tag::getTagsByTopic($model->topic_id);

            }

            return $this->render('create', [
                'model' => $model,
                'tags' => $tags,
                'topic' => $topic
            ]);
        }
    }

    /**
     * Updates an existing Article model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);


        if ($model->load(Yii::$app->request->post())) {
            $model->check = Article::CHECK_WAIT; //编辑过的文章要重新审核
            $event = $this->generateEvent($model);


            if( $model->modify() ){
                //修改成功
                //触发计数事件
                $model->trigger(Article::EVENT_AFTER_PUT, $event);

                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {

            //保留模型数据
            $topic = [];
            $tags = [];
            if(!empty($model->topic_id)){
                //获取当前话题数据
                $topic = Topic::getSimpleData($model->topic_id);

                //获取当前话题所有可用标签
                $tags = Tag::getTagsByTopic($model->topic_id);

            }

            //关联文章内容
            $model->getRelationContent();

            //关联标签
            $model->getRelationTagsId();

            return $this->render('update', [
                'model' => $model,
                'tags' => $tags,
                'topic' => $topic
            ]);
        }
    }

    /**
     * 删除文章到回收站
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $affect = $model->updateAttributes(['status'=>Article::STATUS_RECYCLE]);
        if($affect === false){
            //提示一下
            Yii::$app->session->setFlash('error', '删除文章失败，请重试。');
            return $this->redirect(['article/view','id'=>$id]);
        }
        //触发计数事件
        $model->trigger(Article::EVENT_AFTER_REC);

        return $this->redirect(['index']);
    }

    /**
     * #彻底删除文章
     */
    public function actionDiscard($id){
        $model = $this->findModel($id);

        if( !$model->discard() ){
            Yii::$app->session->setFlash('error', '删除文章失败，请重试。');
            return $this->redirect(['article/view','id'=>$id]);
        }
        return $this->redirect(['index']);
    }

    /**
     * 恢复文章
     */
    public function actionRestore($id){
        $model = $this->findModel($id);
        $affect = $model->updateAttributes(['status'=>Article::STATUS_NORMAL]);
        if($affect === false){
            //提示一下
            Yii::$app->session->setFlash('error', '恢复文章失败，请重试。');
            return $this->refresh();
        }
        //触发计数事件
        $model->trigger(Article::EVENT_AFTER_RES);

        return $this->redirect(['index']);
    }

    /**
     * Finds the Article model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Article the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Article::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    
    
    /**
     * 批量操作
     */
    public function actionOperate(){


        $ids = Yii::$app->request->get('ids');
        $operate = Yii::$app->request->get('operate');

        //数据验证
        if(empty($ids) || empty($operate) || !is_array($ids) || count($ids) > 20){
            throw new BadRequestHttpException('请求错误。');
        }



        try{
            //计数数据
            switch ($operate) {
                case 'batchCheck':
                    $count = Article::getCheckCountNum($ids);
                    break;
                case 'batchDelete':
                    $count = Article::getCountNum($ids);
                    break;
                case 'batchRestore':
                    $count = Article::getRestoreCountNum($ids);
                    break;
                default:
                    $count = [];
                    break;
            }


            Yii::$app->response->format = Response::FORMAT_JSON;
            //调用可变函数
            return $this->$operate($ids, $count);

        }catch (UnknownMethodException $e){
            //函数未定义直接跳到列表页
            Yii::error($e->getMessage(), __METHOD__);
            return $this->redirect(['index']);

        }catch (Exception $e){

            //返回错误信息
            Yii::error($e->getMessage(), __METHOD__);
            return [
                'errcode' => 1,
                'message' => $e->getMessage(),
            ];

        }
    }
    public function actionTest(){
        $count = Article::getCountNum([1]);
        Article::batchOperateCount($count, 'dec');
        die;
    }


    /**
     * 批量删除
     */
    private function batchDelete($ids, $count){

        $affect = Article::updateAll([
            //属性
            'status' => Article::STATUS_RECYCLE
        ],[
            //条件
            'and',
            ['in', 'id', $ids],
            ['!=', 'status', Article::STATUS_RECYCLE]

        ]);

        if($affect === false){
            throw new Exception('批量删除文章失败，请重试。');
        }

        //批量删除 话题收录数减操作***(非公示审核通过的文章不管)
        Article::batchOperateCount($count, 'dec');


        return [
            'errcode' => 0,
            'message' => '批量删除文章成功。',
        ];
    }
    /**
     * 批量恢复
     */
    private function batchRestore($ids, $count){
        $affect = Article::updateAll([
            //属性
            'status' => Article::STATUS_NORMAL
        ],[
            //条件
            'and',
            ['in', 'id', $ids],
            ['=', 'status', Article::STATUS_RECYCLE]

        ]);

        if($affect === false){
            throw new Exception('批量恢复文章失败，请重试。');
        }

        //批量恢复 话题收录数加操作***(非公示审核通过的文章不管)
        Article::batchOperateCount($count);

        return [
            'errcode' => 0,
            'message' => '批量恢复文章成功。',
        ];
    }
    /**
     * 批量发布
     */
    /*private function batchPublish($ids, $count){
        $affect = Article::updateAll([
            //属性
            'status' => Article::STATUS_NORMAL
        ],[
            //条件
            'and',
            ['in', 'id', $ids],
            ['=', 'status', Article::STATUS_DRAFT]

        ]);

        if($affect === false){
            throw new Exception('批量发布文章失败，请重试。');
        }
        return [
            'errcode' => 0,
            'message' => '批量发布文章成功。',
        ];
    }*/
    /**
     * 批量审核
     */
    private function batchCheck($ids, $count){
        $affect = Article::updateAll([
            //属性
            'check' => Article::CHECK_ADOPT
        ],[
            //条件
            'id' => $ids,
            'check' => Article::CHECK_WAIT, //等待审核的
            'status' => Article::STATUS_NORMAL//公示文章


        ]);

        if($affect === false){
            throw new Exception('批量审核文章失败，请重试。');
        }

        //批量审核 话题收录数减操作***
        Article::batchOperateCount($count);


        return [
            'errcode' => 0,
            'message' => '批量审核文章成功。',
        ];
    }

    /**
     * $生成文章修改事件 携带数据
     * @param $model
     */
    private function generateEvent($model){
        if(!( $model instanceof Article)){
            return null;
        }

        $event = new ArticlePutEvent();
        $event->status = $model->status;
        $event->check = $model->check;
        $event->topic_id = $model->topic_id;
        $event->article_id = $model->id;

        //如果修改过一些属性
        if($model->getDirtyAttributes(['status'])){
            $event->oldStatus = $model->getOldAttribute('status');
        }
        if($model->getDirtyAttributes(['check'])){
            $event->oldCheck = $model->getOldAttribute('check');
        }
        if($model->getDirtyAttributes(['topic_id'])){
            $event->topic_id = $model->getOldAttribute('topic_id');
        }

        return $event;
    }

}
