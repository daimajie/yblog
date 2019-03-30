<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/2/20
 * Time : 13:43
 */

namespace app\modules\home\modules\content\controllers;


use app\models\content\Article;
use app\modules\home\controllers\BaseController;
use yii\helpers\VarDumper;
use yii\web\NotFoundHttpException;

class ArticleController extends BaseController
{
    //显示文章
    public function actionView($id){

        $model = $this->findModel($id);

        //增加访问次数
        $model->updateCounters(['visited' => 1]);

        return $this->render('view',[
            'model' => $model,
            'prevAndNext' => Article::getPrevNext($id, $model['topic_id'])
        ]);
    }


    /**
     * 获取指定文章
     */
    protected function findModel($id)
    {
        if (($model = Article::singleArticle($id)) !== null) {

            return $model;

        }
        throw new NotFoundHttpException('您请求的页面不存在.');
    }
}