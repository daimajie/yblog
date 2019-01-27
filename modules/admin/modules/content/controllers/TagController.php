<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/1/25
 * Time : 9:58
 */

namespace app\modules\admin\modules\content\controllers;
use app\modules\admin\controllers\BaseController;
use app\modules\admin\models\Tag;
use Yii;
use yii\base\Exception;
use yii\web\BadRequestHttpException;
use yii\web\Response;

class TagController extends BaseController
{
    //根据话题获取标签
    public function actionGetTags(){
        Yii::$app->response->format = Response::FORMAT_JSON;

        try{
            //判断请求类型
            if(!Yii::$app->request->isAjax){
                throw new BadRequestHttpException('请求方式不被允许。');
            }

            //接受参数
            $topic_id = (int) Yii::$app->request->get('topic_id');
            if($topic_id <= 0)
                throw new Exception('请求参数错误。');

            //获取标签数据
            $tags = Tag::getTagsByTopic($topic_id);

            if(!$tags)
                throw new Exception('没有相关数据。');

            return [
                'errcode' => 0,
                'data' => $tags,
            ];

        }catch (Exception $e){

            return [
                'errcode' => 0,
                'data' => [],
                'errmsg' => $e->getMessage()
            ];
        }
    }
}