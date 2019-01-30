<?php

namespace app\modules\admin\modules\content\controllers;

use Yii;
use app\modules\admin\models\Topic;
use app\modules\admin\models\SearchTopic;
use app\modules\admin\controllers\BaseController;
use yii\base\Exception;
use yii\helpers\VarDumper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use app\widgets\upload\actions\UploadAction;

/**
 * TopicController implements the CRUD actions for Topic model.
 */
class TopicController extends BaseController
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
                ],
            ],
        ];
    }

    //独立方法
    public function actions()
    {
        return [
            'upload' => UploadAction::class,
        ];
    }

    /**
     * Lists all Topic models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchTopic();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Topic model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if(Yii::$app->request->isPost){
            //设置场景
            $model->scenario = Topic::SCENARIO_STATUS;

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
     * Creates a new Topic model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Topic();

        if ($model->load(Yii::$app->request->post()) && $model->store()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Topic model.
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
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Topic model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try{
            $this->findModel($id)->del();
        }catch (Exception $e){
            Yii::$app->session->setFlash('error',$e->getMessage());
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Topic model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Topic the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Topic::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }




}
