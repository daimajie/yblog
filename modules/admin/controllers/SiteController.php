<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/2/13
 * Time : 22:08
 */

namespace app\modules\admin\controllers;

use app\models\content\Article;
use app\models\content\Topic;
use app\models\member\User;
use app\widgets\ueditor\UploadFileAction;
use app\models\motion\Contact;
use Yii;
use yii\helpers\VarDumper;

/**
 * 公共控制器
 * Class SiteController
 * @package app\modules\admin\controllers
 */
class SiteController extends BaseController
{
    const CACHE_COUNT = 'count';

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => 'yii\web\ErrorAction',
            'upload-file' => UploadFileAction::class

        ];
    }


    public function actionIndex(){

        $cache = Yii::$app->cache;
        $count = $cache->getOrSet(self::CACHE_COUNT,function(){
                return [
                    'messageCount' => Contact::find()->count(),
                    'userCount' => User::find()->count(),
                    'articleCount' => Article::find()->where([
                        'check' => Article::CHECK_ADOPT,
                        'status' => Article::STATUS_NORMAL
                    ])->count(),
                    'topicCount' => Topic::find()->where([
                        'check' => Topic::CHECK_ADOPT,
                        'status' => [Topic::STATUS_NORMAL, Topic::STATUS_FINISH],
                        'secrecy' => Topic::SECR_PUBLIC
                    ])->count()
                ];
        }, 3600 * 2);

        return $this->render('index',[
            'count' => $count
        ]);
    }
}