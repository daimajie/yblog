<?php

namespace app\modules\admin\modules\member\controllers;

use app\models\member\UserForm;
use app\models\rbac\AuthAssignment;
use app\models\rbac\AuthItem;
use Yii;
use app\models\member\UserForm as User;
use app\models\member\SearchUser;
use app\modules\admin\controllers\BaseController;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\helpers\VarDumper;
use yii\rbac\Assignment;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends BaseController
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
                    'assignment' => ['POST'],
                ],
            ],
        ]);
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchUser();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {

        $model = $this->findModel($id);



        if(Yii::$app->request->isPost){
            //设置场景
            $model->scenario = UserForm::SCENARIO_STATUS;

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                //设置状态成功
                $this->refresh();

            }else{
                //设置状态失败
                //do nothing
            }

        }

        // 授权数据
        $routerDataProvider = new ActiveDataProvider([
            'query' => AuthItem::find()->with(['ruleName'])->where(['type'=>AuthItem::TYPE_ROLE]),
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
            'pagination' => [
                'pageSize' => 100
            ]
        ]);

        //获取选中的角色
        $hasRoles = $this->getAuthManager()->getRolesByUser($model->id);
        $rolesName = array_keys($hasRoles);






        return $this->render('view', [
            'model' => $model,
            'routerDataProvider' => $routerDataProvider,
            'rolesName' => $rolesName
        ]);
    }

    //分派角色
    public function actionAssignment(){
        $user_id = (int) Yii::$app->request->post('user_id');
        $roles = Yii::$app->request->post('roles');

        if(empty($user_id)){
            throw new Exception('传递参数错误。');
        }

        $user = $this->findModel($user_id);

        //清空该用户所有的角色
        if(AuthAssignment::deleteAll(['user_id'=>$user->id]) === false){
            throw new Exception('清空用户角色失败。');
        }

        //指派新角色
        if(empty($roles) || !is_array($roles)){

            return $this->redirect(['user/view','id'=>$user->id]);
        }

        $auth = $this->getAuthManager();

        foreach ($roles as $item){
            //获取角色
            $role = $auth->getRole($item);
            $auth->assign($role, $user->id);
        }


        return $this->redirect(['user/view','id'=>$user->id]);

    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();
        $model->scenario = UserForm::SCENARIO_ADD;

        if ($model->load(Yii::$app->request->post()) && $model->store()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = UserForm::SCENARIO_PUT;

        if ($model->load(Yii::$app->request->post()) && $model->renew()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        //当前用户有文章禁止删除
        if($model->getArticleSum()){
            Yii::$app->session->setFlash('error', '当前用户发表了文章，禁止删除。');
            return $this->redirect(['user/view', 'id'=>$model->id]);
        }
        $model->delete();

        //删除该用户与权限的关联
        AuthAssignment::deleteAll(['user_id'=>$model->id]);

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    //获取authManager
    private function getAuthManager(){
        return Yii::$app->authManager;
    }
}
