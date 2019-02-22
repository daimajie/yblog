<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/2/21
 * Time : 12:38
 */

namespace app\modules\home\modules\motion\controllers;


use app\models\motion\Comment;
use app\modules\home\controllers\BaseController;
use yii\base\Exception;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;

class CommentController extends BaseController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create'],
                'rules' => [
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'create' => ['post'],
                    'fetch' => ['get'],
                ],
            ],
            'format' => [
                'class' => 'yii\filters\ContentNegotiator',
                'only' => ['create', 'fetch'],
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    /**
     * Creates a new Comment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        try{
            //验证请求方式
            if(!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('请求方式不被允许。');

            $model = new Comment();
            $model->setAttributes(Yii::$app->request->post());


            if (!$model->save()) {
                throw new Exception($model->getErrors()[0]);
            }

            //记录评论数


            return [
                'errcode' => 0,
                'message' => '评论成功。'
            ];
        }catch (Exception $e){
            return [
                'errcode' => 1,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * 获取评论列表
     */
    public function actionFetch(){
        try{
            //验证请求方式
            if(!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('请求方式不被允许。');

            //接受参数
            $article_id = (int) Yii::$app->request->get('article_id', 0);
            if($article_id <= 0)
                throw new NotFoundHttpException('没有找到相关数据。');

            //页码参数
            $page = (int) Yii::$app->request->get('page');
            $limit = (int) Yii::$app->request->get('limit');
            if($page <= 0) $page = 1;
            if($limit < 5 || $limit > 15) $limit = 5;



            return [
                'errcode' => 0,
                'data' => Comment::getComments($article_id, $page, $limit)
            ];
        }catch (\Exception $e){
            return [
                'errcode' => 1,
                'message' => $e->getMessage(),
            ];
        }
    }
}