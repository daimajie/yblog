<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/3/9
 * Time : 21:49
 */

namespace app\modules\home\modules\motion\controllers;


use app\models\motion\Contact;
use app\modules\home\controllers\BaseController;
use yii\filters\AccessControl;
use Yii;

class ContactController extends BaseController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(),[
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
        ]);
    }


    //联系我们
    public function actionCreate(){

        $model = new Contact();

        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post()) && $model->save()){
                //提交联系成功
                Yii::$app->session->setFlash('success', '提交消息成功。');
                return $this->refresh();
            }
        }

        return $this->render('create',[
            'model' => $model
        ]);
    }

}