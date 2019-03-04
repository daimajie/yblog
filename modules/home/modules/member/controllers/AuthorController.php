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
use yii\web\NotFoundHttpException;

class AuthorController extends BaseController
{
    //作者
    public function actionIndex($id){

        $model = $this->findModel($id);



        $searchModel = new SearchArticle();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, null, $id);

        //作者最近更新的话题列表
        $topics = Topic::getActiveTopicsByUser($id, 5);

        //获取作者私密话题
        $secrecy = Topic::getSecrecyTopicByUser($id);

        return $this->render('index', [
            'model' => $model, //作者模型
            'dataProvider' => $dataProvider,
            'topics' => $topics,
            'topicCount' => Topic::getCountByUser($id),
            'secrecy' => $secrecy
        ]);
    }

    /**
     * 获取用户模型
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            //是否是作者
            if($model->author >= 0)
                return $model;
        }
        throw new NotFoundHttpException('您请求的页面不存在。');
    }
}