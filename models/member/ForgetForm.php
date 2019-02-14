<?php
namespace app\models\member;
use app\components\EmailService;
use yii\base\Exception;
use yii\base\Model;
use Yii;
class ForgetForm extends Model
{
    const SCENARIO_FORGET = 'forget';
    const SCENARIO_RESET = 'reset';

    public $username;
    public $email;
    public $captcha;

    public $new_password;
    public $re_password;

    public $token;

    private $_user = false;


    public function rules()
    {
        return [
            [['username', 'email', 'captcha'], 'required', 'on' => [static::SCENARIO_FORGET]],
            [['captcha'], 'captcha', 'captchaAction' => 'index/captcha', 'on' => [static::SCENARIO_FORGET]],
            [['email'], 'email', 'on'=>[static::SCENARIO_FORGET]],
            [['email'], 'checkEmail', 'on'=>[static::SCENARIO_FORGET]],

            [['new_password', 're_password'], 'required', 'on' => [static::SCENARIO_RESET]],
            [['re_password'], 'compare', 'compareAttribute' => 'new_password', 'on' => [static::SCENARIO_RESET]]
        ];
    }
    /**
     * 验证用户名邮箱是否匹配
     */
    public function checkEmail($attr){
        if ($this->hasErrors())
            return false;
        $user = $this->getUser();
        if( !$user ){
            $this->addError($attr, '用户名与邮箱不匹配.');
            return false;
        }
        return true;
    }

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'email' => '邮箱',
            'new_password' => '新密码',
            're_password' => '重复密码',
            'captcha' => '验证码'
        ];
    }
    public function sendEmail(){
        if(!$this->validate()) return false;


        if( !EmailService::sendLimit(3, 5 * 60) ){
            throw new Exception('发送邮件太多啦，休息一下吧。');
        }

        //发送邮件
        $user = $this->getUser();
        $user->generatePasswordResetToken();
        if (!$user->save()) {
            throw new Exception('生成重置链接失败，请重试。');
        }

        $fromEmail = Yii::$app->params['adminEmail'];
        $toEmail = $this->email;
        $subject = Yii::$app->name . ' - 重置密码。';
        $viewFile = 'message/password-reset-token';
        $var = [
            'user' => $user,
            'title' => $subject
        ];

        if( !EmailService::sendEmail($fromEmail, $toEmail, $subject, $viewFile, $var) ){
            throw new Exception('发送重置链接失败，请重试。。');
        }

        return true;
    }
    //重置密码
    public function reset(){
        if( !$this->validate())
            return false;

        //根据token获取用户
        $user = $this->getUser();
        if( !$user ) return false;

        $user->generatePasswordHash($this->new_password);
        return $user->save(false);
    }
    //获取用户实例
    private function getUser(){
        if($this->_user !== false){
            return $this->_user;
        }
        if($this->scenario === static::SCENARIO_FORGET){
            $this->_user = $this->getUserByUsernameAndEmail($this->username, $this->email);
        }
        if ($this->scenario === static::SCENARIO_RESET){
            $this->_user = $this->getUserByResetPasswordToken($this->token);
        }
        return $this->_user;
    }
    /**
     * 根据邮箱和用户名获取用户实例
     * @param string $username
     * @param string $password
     * @return User | null
     */
    private function getUserByUsernameAndEmail($username, $password){
        return User::findByUsernameAndEmail($username, $password);
    }
    /**
     * 根据重置密码token 获取用户实例
     * @param $token
     * @return User|array|null|\yii\db\ActiveRecord
     */
    private function getUserByResetPasswordToken($token){
        return User::findByPasswordResetToken($token);
    }
}