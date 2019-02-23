<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/2/21
 * Time : 12:38
 */

namespace app\modules\home\modules\motion\controllers;


use app\components\Helper;
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
    //session限数key
    const COMMENT_LIMIT_KEY = 'comment_limit';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create','delete'],
                'rules' => [
                    [
                        'actions' => ['create','delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'create' => ['post'],
                    'delete' => ['post'],
                    'fetch' => ['get'],
                ],

            ],
            'format' => [
                'class' => 'yii\filters\ContentNegotiator',
                'only' => ['create', 'fetch', 'delete'],
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

            //限制发布评论频率 邮件限速 5分钟内只能发送3条
            if( !Helper::setLimit(self::COMMENT_LIMIT_KEY, 3, 300) ){
                throw new Exception('发布评论太多了，休息一下吧。');
            }

            $model = new Comment();
            $model->setAttributes(Yii::$app->request->post());


            if (!$model->save()) {
                throw new Exception($model->getErrors()[0]);
            }


            return [
                'errcode' => 0,
                'message' => '评论成功。'
            ];
        }catch (MethodNotAllowedHttpException $e){
            //记录日志
            Yii::error($e->getMessage(), __CLASS__);
            return $this->goHome();
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
            if($limit < 5 || $limit > 15) $limit = 1;



            return [
                'errcode' => 0,
                'data' => Comment::getComments($article_id, $page, $limit)
            ];
        }catch (MethodNotAllowedHttpException $e){
            //记录日志
            Yii::error($e->getMessage(), __CLASS__);
            return $this->goHome();

        }catch (Exception $e){
            return [
                'errcode' => 1,
                'message' => $e->getMessage(),
            ];
        }
    }
    /**
     * delete Comment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionDelete()
    {
        try{
            //验证请求方式
            if(!Yii::$app->request->isAjax)
                throw new MethodNotAllowedHttpException('请求方式不被允许。');

            $comment_id = (int) Yii::$app->request->post('comment_id');

            if($comment_id <= 0) throw new Exception('请求参数错误。');


            //是否是评论所有者
            $model = Comment::findOne($comment_id);

            if(!$model)
                throw new NotFoundHttpException('没有找到相关数据。');

            if($model->user_id !== Yii::$app->user->id)
                throw new Exception('当前评论您无权删除。');

            //删除(如果是评论 连同回复一起删除)
            if($model->delete() === false)
                throw new Exception('删除评论失败,请重试。');


            //记录评论数


            return [
                'errcode' => 0,
                'message' => '删除评论成功。'
            ];
        }catch (MethodNotAllowedHttpException $e){
            //记录日志
            Yii::error($e->getMessage(), __CLASS__);
            return $this->goHome();
        }catch (Exception $e){
            return [
                'errcode' => 1,
                'message' => $e->getMessage(),
            ];
        }
    }


}