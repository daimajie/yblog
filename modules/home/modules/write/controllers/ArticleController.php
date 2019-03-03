<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/3/2
 * Time : 12:56
 */

namespace app\modules\home\modules\write\controllers;


use app\components\events\ArticlePutEvent;
use app\models\content\Article;
use app\models\content\Tag;
use app\widgets\upload\actions\UploadAction;
use app\models\content\Topic;
use app\modules\home\controllers\BaseController;
use app\modules\home\modules\write\models\ArticleForm;
use app\modules\home\modules\write\models\SearchArticle;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\VarDumper;
use yii\web\NotFoundHttpException;
use app\widgets\select2\actions\SelectAction;
use Yii;

class ArticleController extends BaseController
{
    public $user; //当前用户

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'only' => ['validateForm','create','update','delete','get-tags'],
                'rules' => [
                    [
                        'actions' => ['validateForm','create','update','delete','get-tags'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function init()
    {
        parent::init();
        $this->user = Yii::$app->user->identity;
    }

    public function actions()
    {
        return [
            'select' => [
                'class' => SelectAction::class,
                'table' => '{{%topic}}',
                'index' => 'id',
                'text' => 'name',
                'limit' => '9',
                'user_id' =>  $this->user->id,
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

    public function beforeAction($action)
    {
        if(parent::beforeAction($action)){
            //不显示页面logo
            $this->view->params['showHeader'] = false;
            return true;
        }
        return false;
    }


    /**
     * #文章列表
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionIndex($id){

        $model = $this->findModel($id);

        $searchModel = new SearchArticle();
        $searchModel->topic_id = $id;

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $this->user->id);

        return $this->render('index', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id){
        $model = $this->findArticleModel($id);

        return $this->render('view',[
            'model' => $model,
            'prevAndNext' => Article::getPrevNext($id, $model['topic_id'])
        ]);
    }

    //创建文章
    public function actionCreate($id){
        $model = new ArticleForm();

        if ($model->load(Yii::$app->request->post()) && $model->store()) {


            //触发计数事件(新建文章初始都是待审核 所以不需要计数)
            //$model->trigger(Article::EVENT_AFTER_ADD);

            return $this->redirect(['view', 'id' => $model->id]);
        }


        //保留模型数据
        $topic = [];
        $tags = [];
        if(!empty($id)){
            //获取当前话题数据
            $topic = Topic::getSimpleData($id);

            //获取当前话题所有可用标签
            $tags = Tag::getTagsByTopic($id);

        }

        return $this->render('create', [
            'model' => $model,
            'tags' => $tags,
            'topic' => $topic
        ]);
    }

    //修改文章
    public function actionUpdate($id){
        $model = $this->findArticleModel($id);


        if ($model->load(Yii::$app->request->post())) {
            $model->check = Article::CHECK_WAIT; //编辑过的文章要重新审核
            $event = $this->generateEvent($model);


            if( $model->modify() ){
                //修改成功
                //触发计数事件
                $model->trigger(Article::EVENT_AFTER_PUT, $event);

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        //保留模型数据
        //获取当前话题数据
        $topic = Topic::getSimpleData($model->topic_id);

        //获取当前话题所有可用标签
        $tags = Tag::getTagsByTopic($model->topic_id);

        //关联文章内容
        $model->getRelationContent();

        //关联标签
        $model->getRelationTagsId();

        return $this->render('create', [
            'model' => $model,
            'tags' => $tags,
            'topic' => $topic
        ]);
    }

    //删除至回收站
    public function actionDelete($id){
        $model = $this->findArticleModel($id);
        $model->status = Article::STATUS_RECYCLE;

        $event = $this->generateEvent($model);


        //$affect = $model->updateAttributes(['status'=>Article::STATUS_RECYCLE]);
        if($model->save(false) === false){
            //提示一下
            Yii::$app->session->setFlash('error', '删除文章失败，请重试。');
        }else{
            //触发计数事件
            $model->trigger(Article::EVENT_AFTER_REC, $event);
        }

        return $this->redirect(['index','id'=>$model->topic_id]);
    }



    //获取文章模型
    public function findArticleModel($id){

        $model = ArticleForm::find()->where([
            'id'=> $id,
            'user_id' => $this->user->id,
        ])
            ->andWhere(['!=', 'status', Article::STATUS_RECYCLE])
            ->one();
        if ($model) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }



    /**
     * #获取话题模型
     * @param $id
     * @return Topic|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Topic::findOne($id)) !== null) {

            if($model->status != Topic::STATUS_RECYCLE && $model->user_id == $this->user->id)
                return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
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
        $event->user_id = $model->user_id;

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