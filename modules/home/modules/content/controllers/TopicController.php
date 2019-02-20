<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/2/19
 * Time : 12:19
 */

namespace app\modules\home\modules\content\controllers;


use app\models\content\Topic;
use app\modules\home\models\content\SearchArticle;
use app\modules\home\models\content\SearchTopic;
use Yii;
use app\models\content\Category;
use app\modules\home\controllers\BaseController;
use yii\web\NotFoundHttpException;

class TopicController extends BaseController
{
    /**
     * #话题列表
     * @return string
     */
    public function actionIndex(){

        $searchModel = new SearchTopic();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'category_items' => Category::dropItems(),
        ]);
    }

    /**
     * #文章列表
     * @param $id
     * @return string
     */
    public function actionView($id){

        $model = $this->findModel($id);
        $searchModel = new SearchArticle();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'cloudTags' => $model->getTags()->asArray()->all()
        ]);
    }

    /**
     * #过滤 私有话题 非审核通过话题 和已删除话题
     * @param $id
     * @return Topic|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Topic::findOne($id)) !== null) {

            if($model->secrecy != Topic::SECR_PRIVATE &&
                $model->check == Topic::CHECK_ADOPT &&
                $model->status != Topic::STATUS_RECYCLE)
                return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }


}