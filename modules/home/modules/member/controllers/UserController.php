<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/2/25
 * Time : 21:01
 */

namespace app\modules\home\modules\member\controllers;


use app\models\member\Profile;
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
    private $_user;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['setting','avatar','password','email','qrcode','upload','photo','set-qrcode'],
                'rules' => [
                    [
                        'actions' => ['setting','avatar','password','email','qrcode','upload','photo','set-qrcode'],
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
            'photo' => [
                'class' => UploadAction::class,
                'name' => 'photo',
                'subDir' => 'photo',
                'thumb' => [
                    'width' => 270,
                    'height' => 203
                ]
            ],
            'qrcode' => [
                'class' => UploadAction::class,
                'name' => 'qrcode',
                'subDir' => 'qrcode',
                'thumb' => [
                    'width' => 300,
                    'height' => 300
                ]
            ],
        ];
    }


    //账号设置
    public function actionSetting(){
        $model = $this->getUser();
        $model->scenario = UserForm::SCENARIO_SETTING;

        $profile = $this->getProfile($model->id);

        if(Yii::$app->request->isPost){

            //获取头像地址
            $oldPhoto = $profile->photo;

            if($model->load(Yii::$app->request->post()) && $profile->load(Yii::$app->request->post())){


                $isValid = $model->validate();
                $isValid = $profile->validate() && $isValid;

                if($isValid){
                    $model->save(false);
                    $profile->save(false);

                    //删除原有照片
                    if($oldPhoto !== $profile->photo){
                        Helper::delImage($oldPhoto);
                    }

                    //修改成功
                    Yii::$app->session->setFlash('success', '修改成功。');
                    return $this->refresh();
                }

            }

            Yii::$app->session->setFlash('error', $model->getFirstError('nickname'));
        }

        return $this->render('setting',[
            'model' => $model,
            'profile' => $profile
        ]);
    }

    //修改头像
    public function actionAvatar(){
        $model = $this->getUser();
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
        $model = $this->getUser();
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
        $model = $this->getUser();
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

    //设置用户基本信息
    public function actionSetQrcode(){
        $model = $this->getUser();

        $profile = $this->getProfile($model->id);

        if(Yii::$app->request->isPost){
            $oldQrcode = $profile->qrcode;

            if($profile->load(Yii::$app->request->post()) && $profile->save()){
                //如果修改了二维码 删除原来的
                if($oldQrcode != $profile->qrcode)
                    Helper::delImage($oldQrcode);

                //设置二维码成功
                Yii::$app->session->setFlash('success', '设置成功。');
                return $this->refresh();
            }
        }

        return $this->render('profile',[
            'model' => $model,
            'profile' => $profile
        ]);
    }

    //获取当前用户模型
    private function getUser(){
        if(!empty($this->_user))
            return $this->_user;

        //获取当前用户模型
        $this->_user = User::findOne(Yii::$app->user->id);

        return $this->_user;
    }



    //获取详情模型
    public function getProfile($id){
        //详情模型
        $profile = Profile::find()->where([
            'user_id' => $id
        ])->one();

        if($profile)

            return $profile;
        else{
            //如果没有设置就新建一个详情模型
            $model = new Profile();
            $model->user_id = $id;
            return $model;
        }

    }
}