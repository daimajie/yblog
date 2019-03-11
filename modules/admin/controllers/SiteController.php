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

/**
 * 公共控制器
 * Class SiteController
 * @package app\modules\admin\controllers
 */
class SiteController extends BaseController
{
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



        return $this->render('index',[
            'message' => Contact::find()->count(),
            'user' => User::find()->count(),
            'article' => Article::find()->where([

            ])->count(),
            'topic' => Topic::find()->where([

            ])->count()
        ]);
    }
}