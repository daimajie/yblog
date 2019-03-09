<?php

namespace app\modules\home\modules\write\controllers;

use Yii;
use app\modules\home\modules\write\models\TagForm as Tag;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\Exception;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * TagController implements the CRUD actions for Tag model.
 */
class TagController extends WriteBaseController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(),[
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ]);
    }

    /**
     * ajax 验证
     * @param $id int #标签id
     */
    public function actionValidateForm($id = null){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = $id === null ? new Tag() : $this->findModel($id);
        $model->load(Yii::$app->request->post());

        return ActiveForm::validate($model);


    }

    /**
     * Creates a new Tag model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($topic_id)
    {

        $model = new Tag();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['topic/view', 'id'=>$topic_id]);
        } else {
            return $this->renderAjax('create', [
                'model' => $model,
                'topic_id' => (int) $topic_id
            ]);
        }
    }

    /**
     * Updates an existing Tag model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($topic_id,$id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['topic/view', 'id'=>$topic_id]);
        } else {
            return $this->renderAjax('update', [
                'model' => $model,
                'topic_id' => (int) $topic_id
            ]);
        }
    }

    /**
     * Deletes an existing Tag model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $id = (int) Yii::$app->request->post('id');

        if($this->findModel($id)->discard()){
            return [
                'errcode' => 0,
                'errmsg' => '删除成功。'
            ];
        }
        return [
            'errcode' => 1,
            'errmsg' => '删除失败，请重试。'
        ];

    }

    /**
     * Finds the Tag model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Tag the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tag::findOne($id)) !== null && $model->user_id == Yii::$app->user->id) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    //****根据话题获取标签
    public function actionGetTags(){
        Yii::$app->response->format = Response::FORMAT_JSON;

        try{
            //判断请求类型
            if(!Yii::$app->request->isAjax){
                throw new BadRequestHttpException('请求方式不被允许。');
            }

            //接受参数
            $topic_id = (int) Yii::$app->request->get('topic_id');
            if($topic_id <= 0)
                throw new Exception('请求参数错误。');

            //获取标签数据
            $tags = Tag::getTagsByTopic($topic_id);

            if(!$tags)
                throw new Exception('没有相关数据。');

            return [
                'errcode' => 0,
                'data' => $tags,
            ];

        }catch (Exception $e){

            return [
                'errcode' => 0,
                'data' => [],
                'errmsg' => $e->getMessage()
            ];
        }
    }
}
