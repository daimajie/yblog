<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/3/1
 * Time : 16:55
 */

namespace app\modules\home\modules\write\controllers;


use app\models\content\Category;
use app\modules\home\modules\write\models\SearchArticle;
use app\models\content\Topic;
use app\modules\home\modules\write\models\SearchTopic;
use app\modules\home\modules\write\models\TopicForm;
use app\widgets\upload\actions\UploadAction;
use Yii;
use yii\base\Exception;
use yii\web\NotFoundHttpException;

class TopicController extends WriteBaseController
{
    public $user; //当前用户


    //独立方法
    public function actions()
    {
        return [
            'upload' => [
                'class' => UploadAction::class,
                'subDir' => 'topic',
                'thumb' => [
                    'width' => 390,
                    'height' => 293
                ]
            ],
        ];
    }

    public function init()
    {
        parent::init();
        $this->user = Yii::$app->user->identity;
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

    //话题列表
    public function actionIndex(){
        $searchModel = new SearchTopic();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $this->user->id);

        return $this->render('index',[
            'dataProvider' => $dataProvider,
            'category_items' => Category::dropItems(),
        ]);
    }

    //话题创建
    public function actionCreate(){
        $model = new TopicForm();

        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post()) && $model->store()){
                //创建成功
                return $this->redirect(['index']);
            }
            //话题创建失败
        }

        return $this->render('create',[
            'model' => $model,
            'category_items' => Category::dropItems(),
        ]);
    }

    //话题修改
    public function actionUpdate($id){
        $model = $this->findTopicModel($id);

        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post()) && $model->modify()){
                //创建成功
                return $this->redirect(['index']);
            }
            //话题创建失败
            Yii::$app->session->setFlash('error', $model->getFirstErrors());
        }

        return $this->render('create',[
            'model' => $model,
            'category_items' => Category::dropItems(),
        ]);
    }

    //话题删除
    public function actionDelete($id){

        try{
            $this->findTopicModel($id)->del();
        }catch (Exception $e){
            Yii::$app->session->setFlash('error',$e->getMessage());
            return $this->redirect(['index']);
        }

        return $this->redirect(['index']);
    }

    //话题展示
    public function actionView($id){
        $model = $this->findTopicModel($id);


        return $this->render('view',[
            'model' => $model
        ]);
    }

    //话题文章列表**************************
    public function actionShow($id){
        $model = $this->findTopicModel($id);

        $searchModel = new SearchArticle();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id, $this->user->id);

        return $this->render('show', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'cloudTags' => $model->getTags()->asArray()->all()
        ]);
    }

    //获取模型
    protected function findTopicModel($id)
    {
        if (($model = TopicForm::findOne($id)) !== null) {
            if($model->status != Topic::STATUS_RECYCLE && $model->user_id == $this->user->id)
                return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


}