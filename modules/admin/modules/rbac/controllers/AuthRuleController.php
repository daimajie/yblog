<?php

namespace app\modules\admin\modules\rbac\controllers;

use Yii;
use app\models\rbac\AuthRule;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use app\modules\admin\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AuthRuleController implements the CRUD actions for AuthRule model.
 */
class AuthRuleController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all AuthRule models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => AuthRule::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 创建规则
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AuthRule();

        if ($model->load(Yii::$app->request->post()) && $model->store()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }



    /**
     * Deletes an existing AuthRule model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        if(empty($id))
            throw new Exception('传递参数错误.');

        $auth = Yii::$app->authManager;

        $rule = $auth->getRule($id);

        if(!$rule){
            throw new NotFoundHttpException('您请求的页面没有找到。');
        }

        $auth->remove($rule);

        return $this->redirect(['index']);
    }

}
