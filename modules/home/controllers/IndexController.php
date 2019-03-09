<?php

namespace app\modules\home\controllers;

use app\components\EmailService;
use app\components\Helper;
use app\modules\home\models\content\SearchArticle;
use app\models\member\ForgetForm;
use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\helpers\VarDumper;
use yii\web\MethodNotAllowedHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\member\LoginForm;
use app\models\member\ContactForm;

class IndexController extends BaseController
{
    const EMAIL_LIMIT_KEY = 'email_limit';
    const SEARCH_LIMIT_KEY = 'search_limit';

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout','login','register','forget','reset_password'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['login','register','forget','reset_password'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',

            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'minLength' => 4,
                'maxLength' => 4
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {


        $searchModel = new SearchArticle();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index',[
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * #搜索页
     * @return string
     */
    public function actionSearch(){

        //搜索也要限制频率(5分钟可搜索3次)
        if( !Helper::setLimit(self::SEARCH_LIMIT_KEY, 3, 300) ){
            throw new Exception('搜索次数太多了，休息一下吧。');
        }

        //没有搜索参数就返回到首页
        $params = Yii::$app->request->queryParams;
        if(empty($params['title']))
            return $this->goHome();

        $searchModel = new SearchArticle();
        $dataProvider = $searchModel->search($params);

        //VarDumper::dump($dataProvider,10,1);die;
        return $this->render('search', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        $this->layout = '@app/modules/admin/views/layouts/main-login';

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        $model->scenario = LoginForm::SCENARIO_LOGIN;
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * register action.
     */
    public function actionRegister(){
        $this->layout = '@app/modules/admin/views/layouts/main-login';

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        $model->scenario = LoginForm::SCENARIO_REGISTER;

        if ($model->load(Yii::$app->request->post()) && $model->register()) {
            //注册成功 直接登录
            //return $this->goHome();
            Yii::$app->session->setFlash('success','注册成功，现在可以登录了。');
            return $this->redirect(['index/login']);
        }
        $model->password = '';
        return $this->render('register', [
            'model' => $model,
        ]);
    }

    /**
     * forget action.
     */
    public function actionForget(){
        $this->layout = '@app/modules/admin/views/layouts/main-login';

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new ForgetForm();
        $model->scenario = ForgetForm::SCENARIO_FORGET;

        if(Yii::$app->request->isPost){
            if( $model->load(Yii::$app->request->post()) ){
                try{
                    if( $model->sendEmail() ){
                        Yii::$app->session->setFlash('success', '邮件发送成功，请查收。');
                        //return $this->refresh();
                    }
                }catch (Exception $e){
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
            }
        }
        $model->captcha = '';
        return $this->render('forget',[
            'model' => $model
        ]);
    }

    /**
     * 重置密码
     */
    public function actionResetPassword($token){
        $this->layout = '@app/modules/admin/views/layouts/main-login';

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new ForgetForm([
            'scenario' => ForgetForm::SCENARIO_RESET,
            'token' => trim($token)
        ]);

        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post()) && $model->reset()){
                //重置成功
                Yii::$app->session->setFlash('success', '重置密码成功。现在可以登录了。');
                return $this->redirect(['index/login']);
            }
            //失败
            Yii::$app->session->setFlash('error', '链接已经失效，请重新申请。');
            $model->new_password = $model->re_password = '';
        }

        return $this->render('reset-password',[
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * 发送邮箱验证码
     */
    public function actionSendEmailCaptcha(){
        Yii::$app->response->format = Response::FORMAT_JSON;

        try{
            //请求方式
            if(!Yii::$app->request->isAjax){
                throw new MethodNotAllowedHttpException('请求方式不被允许.');
            }
            //接收邮件地址信息
            $email = trim(Yii::$app->request->post('email'));
            if( !filter_var($email, FILTER_VALIDATE_EMAIL) )
                throw new Exception('邮箱地址错误.');

            //邮件限速 5分钟内只能发送3条
            if( !Helper::setLimit(self::EMAIL_LIMIT_KEY, 3, 300) ){
                throw new Exception('发送邮件太多，休息一下吧。');
            }

            //生成邮箱验证码
            $captcha = EmailService::generateCaptcha(6, 5*60, Yii::$app->params['email_key']);
            if( empty($captcha) ) throw new Exception('创建验证码失败,请重试.');

            //发送邮件
            $fromEmail = Yii::$app->params['adminEmail'];
            $subject = Yii::$app->name . ' - 邮件验证码。';
            $viewFile = 'message/send-email';
            $var = [
                'name' => Yii::$app->name,
                'captcha' => $captcha
            ];
            $ret = EmailService::sendEmail($fromEmail, $email, $subject, $viewFile, $var);
            if( !$ret ){
                throw new Exception('邮件发送失败,请重试.');
            }
            return [
                'errcode' => 0,
                'message' => '已发送邮件'
            ];
        }catch (Exception $e){
            return [
                'errcode' => 1,
                'message' => $e->getMessage()
            ];
        }
    }



}
