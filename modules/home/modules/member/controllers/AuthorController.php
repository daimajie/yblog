<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/2/25
 * Time : 14:39
 */

namespace app\modules\home\modules\member\controllers;


use app\models\content\Topic;
use app\models\member\User;
use app\modules\home\controllers\BaseController;
use app\modules\home\models\content\SearchArticle;
use Yii;
use yii\helpers\VarDumper;
use yii\web\NotFoundHttpException;

class AuthorController extends BaseController
{
    //作者
    public function actionIndex($id){

        $model = $this->findModel($id);



        $searchModel = new SearchArticle();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, null, $id);


        return $this->render('index', [
            'model' => $model, //作者模型
            'dataProvider' => $dataProvider,
            'topicCount' => Topic::getCountByUser($id),
        ]);
    }

    /**
     * 获取用户模型
     */
    protected function findModel($id)
    {
        $model = User::find()
            ->with(['profile'])
            ->where([
                'id' => $id
            ])
            ->one();

        if ($model !== null && $model->author >= 0) {
            return $model;
        }
        throw new NotFoundHttpException('您请求的页面不存在。');
    }
}