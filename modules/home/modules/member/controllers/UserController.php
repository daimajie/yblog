<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/2/25
 * Time : 21:01
 */

namespace app\modules\home\modules\member\controllers;


use app\models\member\UserForm;
use app\modules\home\controllers\BaseController;
use app\widgets\upload\actions\UploadAction;
use Yii;
use yii\filters\AccessControl;
use app\components\Helper;
use app\models\member\UserForm as User;
use yii\web\NotFoundHttpException;

class UserController extends BaseController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['setting','avatar','password','email'],
                'rules' => [
                    [
                        'actions' => ['setting','avatar','password','email'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'upload' => [
                'class' => UploadAction::class,
                'subDir' => 'avatar',
                'thumb' => [
                    'width' => 100,
                    'height' => 100
                ]
            ],
        ];
    }


    //账号设置
    public function actionSetting(){
        $model = $this->findModel(Yii::$app->user->id);
        $model->scenario = UserForm::SCENARIO_SETTING;

        if(Yii::$app->request->isPost){

            if($model->load(Yii::$app->request->post()) && $model->save()){
                Yii::$app->session->setFlash('success', '修改成功。');
                return $this->refresh();
            }

            Yii::$app->session->setFlash('error', $model->getFirstError('nickname'));
        }

        return $this->render('setting',[
            'model' => $model,
        ]);
    }

    //修改头像
    public function actionAvatar(){
        $model = $this->findModel(Yii::$app->user->id);
        $model->scenario = UserForm::SCENARIO_AVATAR;

        if(Yii::$app->request->isPost){
            //获取头像地址
            $oldImage = $model->image;

            if($model->load(Yii::$app->request->post())){

                if($oldImage !== $model->image && $model->save()){
                    Helper::delImage($oldImage);
                    Yii::$app->session->setFlash('success', '设置头像成功。');
                    return $this->refresh();
                }
            }

            Yii::$app->session->setFlash('error', '设置头像失败，请重试。');
        }

        return $this->render('avatar',[
            'model' => $model,
        ]);
    }

    //修改密码
    public function actionPassword(){
        $model = $this->findModel(Yii::$app->user->id);
        $model->scenario = UserForm::SCENARIO_PASSWORD;

        if(Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post()) && $model->renew()) {
                Yii::$app->session->setFlash('success', '设置密码成功。');
                return $this->refresh();
            }
            Yii::$app->session->setFlash('error', $model->getFirstError('password'));
        }

        return $this->render('password',[
            'model' => $model,
        ]);
    }

    //修改邮箱
    public function actionEmail(){
        $model = $this->findModel(Yii::$app->user->id);
        $model->scenario = UserForm::SCENARIO_EMAIL;

        if(Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', '设置邮箱成功。');
                return $this->refresh();
            }
            Yii::$app->session->setFlash('error', $model->getFirstError('email'));
        }

        $model->email = null;
        return $this->render('email',[
            'model' => $model,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}