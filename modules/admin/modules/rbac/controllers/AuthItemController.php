<?php

namespace app\modules\admin\modules\rbac\controllers;

use app\models\rbac\SearchAuthItem;
use Yii;
use app\models\rbac\AuthItem;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use app\modules\admin\controllers\BaseController;
use yii\helpers\VarDumper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AuthItemController implements the CRUD actions for AuthItem model.
 */
class AuthItemController extends BaseController
{
    /**
     * Lists all AuthItem models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new SearchAuthItem();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AuthItem model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if(Yii::$app->request->isPost){
            if($model->addChildren(Yii::$app->request->post())){
                //添加路由成功
                return $this->refresh();
            }
        }

        $auth = Yii::$app->authManager;

        $routers = array_keys($auth->getPermissionsByRole($id));

        $routerDataProvider = new ActiveDataProvider([
            'query' => AuthItem::find()->with(['ruleName'])->where(['type'=>AuthItem::TYPE_ROUTER]),
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
            'pagination' => [
                'pageSize' => 100
            ]
        ]);

        return $this->render('view', [
            'model' => $model,
            'routerDataProvider' =>$routerDataProvider,
            'routers' => $routers
        ]);
    }


    /**
     * 创建权限项目
     */
    public function actionCreate()
    {
        $model = new AuthItem();

        if ($model->load(Yii::$app->request->post()) && $model->store()) {
            if($model->type == AuthItem::TYPE_ROLE)
                return $this->redirect(['view', 'id' => $model->name]);
            else
                return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AuthItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->store()) {
            if($model->type == AuthItem::TYPE_ROLE)
                return $this->redirect(['view', 'id' => $model->name]);
            else
                return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing AuthItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(!AuthItem::delItem($id)){
            Yii::$app->session->setFlash('error', '删除项目失败，请重试。');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the AuthItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return AuthItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AuthItem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
