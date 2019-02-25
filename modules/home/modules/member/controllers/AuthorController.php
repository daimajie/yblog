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
        $searchModel->user_id = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        //作者最近更新的话题列表
        $category = Topic::getActiveCategoryByUser($id, 5);

        return $this->render('index', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'category' => $category,
            'topicCount' => Topic::find()->where(['user_id'=>$id])->count()
        ]);
    }

    /**
     * 写文章
     */
    public function actionWrite(){

    }

    /**
     * 获取用户模型
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            //是否是作者
            if($model->author > 0)
                return $model;
        }
        throw new NotFoundHttpException('您请求的页面不存在。');
    }
}