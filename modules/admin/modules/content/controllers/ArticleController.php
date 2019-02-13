<?php

namespace app\modules\admin\modules\content\controllers;

use app\modules\admin\models\content\Tag;
use app\modules\admin\models\content\Topic;
use app\widgets\select2\actions\SelectAction;
use Prophecy\Exception\Doubler\MethodNotFoundException;
use Yii;
use app\modules\admin\models\content\Article;
use app\modules\admin\models\content\SearchArticle;
use app\modules\admin\controllers\BaseController;
use yii\base\Exception;
use yii\base\UnknownMethodException;
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
                    'width' => 670,
                    'height' => 335
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

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                //设置状态成功
                $this->refresh();

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


        if ($model->load(Yii::$app->request->post()) && $model->modify()) {
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
        $affect = $this->findModel($id)->updateAttributes(['status'=>Article::STATUS_RECYCLE]);
        if($affect === false){
            //提示一下
            Yii::$app->session->setFlash('error', '删除文章失败，请重试。');
            return $this->redirect(['article/view','id'=>$id]);
        }

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
        $affect = $this->findModel($id)->updateAttributes(['status'=>Article::STATUS_NORMAL]);
        if($affect === false){
            //提示一下
            Yii::$app->session->setFlash('error', '恢复文章失败，请重试。');
            return $this->refresh();
        }

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

            Yii::$app->response->format = Response::FORMAT_JSON;
            //调用可变函数
            return $this->$operate($ids);

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


    /**
     * 批量删除
     */
    private function batchDelete($ids){

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
        return [
            'errcode' => 0,
            'message' => '批量删除文章成功。',
        ];
    }
    /**
     * 批量恢复
     */
    private function batchRestore($ids){
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
        return [
            'errcode' => 0,
            'message' => '批量恢复文章成功。',
        ];
    }
    /**
     * 批量发布
     */
    private function batchPublish($ids){
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
    }
    /**
     * 批量审核
     */
    private function batchCheck($ids){
        $affect = Article::updateAll([
            //属性
            'check' => Article::CHECK_ADOPT
        ],[
            //条件
            'and',
            ['in', 'id', $ids],
            ['=', 'status', Article::CHECK_WAIT]

        ]);

        if($affect === false){
            throw new Exception('批量审核文章失败，请重试。');
        }
        return [
            'errcode' => 0,
            'message' => '批量审核文章成功。',
        ];
    }


}
