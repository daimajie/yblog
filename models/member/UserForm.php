<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/2/13
 * Time : 16:25
 */

namespace app\models\member;
use Yii;


class UserForm extends User
{
    //场景
    const SCENARIO_ADD = 'add';
    const SCENARIO_PUT = 'put';

    const SCENARIO_STATUS = 'status';

    const SCENARIO_SETTING = 'setting';
    const SCENARIO_AVATAR = 'avatar';
    const SCENARIO_PASSWORD = 'password';
    const SCENARIO_EMAIL = 'email';

    //属性
    public $password;
    public $re_password;

    public $email_captcha;

    //规则
    public function rules()
    {
        return array_merge(parent::rules(),[
            [['password'], 'required', 'on'=>[self::SCENARIO_ADD]],

            [['status', 'author'], 'required', 'on'=>[self::SCENARIO_STATUS]],
            [['status'], 'integer', 'on'=>[self::SCENARIO_STATUS]],
            [['author'], 'in','range' => [-1, 0], 'on'=>[self::SCENARIO_STATUS]],


            [['password'], 'string', 'length' => [6, 18]],
            [['re_password'], 'compare', 'compareAttribute' => 'password'],

            /*setting center*/
            [['nickname'], 'required', 'on'=>[self::SCENARIO_SETTING]],
            /*set avatar*/
            [['image'], 'required', 'on'=>[self::SCENARIO_AVATAR]],
            /*set password*/
            [['password','re_password'], 'required', 'on'=>[self::SCENARIO_PASSWORD]],

            [['email', 'email_captcha'], 'required', 'on'=>[self::SCENARIO_EMAIL]],
            [['email_captcha'], 'checkEmail', 'on'=>[self::SCENARIO_EMAIL]],
        ]);
    }

    public function scenarios()
    {
        return array_merge(parent::scenarios(),[
            self::SCENARIO_ADD => ['username','nickname','email','password','re_password'],
            self::SCENARIO_PUT => ['username','nickname','email','password','re_password'],
            self::SCENARIO_STATUS => ['status','author'],


            self::SCENARIO_EMAIL => ['email', 'email_captcha'],
            self::SCENARIO_AVATAR => ['image'],
            self::SCENARIO_PASSWORD => ['password','re_password'],
            self::SCENARIO_SETTING => ['nickname'],
        ]);
    }

    /**
     * #验证提交的邮箱验证码
     * @param $attr
     * @return bool
     */
    public function checkEmail($attr){
        if($this->hasErrors())
            return false;
        //查看是否是原邮箱
        if($this->email === $this->getOldAttribute('email')){
            $this->addError($attr, '不能使用原来的邮箱地址。');
            return false;
        }

        //获取session验证码数据
        $email_key = Yii::$app->params['email_key'];
        $session = Yii::$app->session;
        $data = $session->get($email_key,'');

        //没有验证数据
        if (empty($data)){
            $this->addError($attr, '数据验证失败，请重试。');
            return false;
        }

        //判断时间
        if((time() - $data['lifetime']) > $data['start_at']){
            $this->addError($attr, '验证码有效时间已过，请重新发送');
            return false;
        }

        //判断是否一致
        if($data['captcha'] !== $this->email_captcha){
            $this->addError($attr, '验证码填写不正确，请查证。');
            return false;
        }

        //清空captcha
        $session->remove($email_key);
        return true;
    }



    //label
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(),[
            'password' => '密码',
            're_password' => '重复密码',
            'new_email' => '新邮箱',
            'email_captcha' => '邮箱验证码'
        ]);
    }

    //创建用户
    public function store(){
        //验证数据
        if ( !$this->validate() ){
            return false;
        }

        //丰富数据
        $this->generatePasswordHash($this->password);
        $this->generateAuthKey();

        return $this->save(false);
    }

    /**
     * 更新用户
     */
    public function renew(){
        if( !$this->validate() )
            return false;

        if( !empty($this->password) ){
            $this->generatePasswordHash($this->password);
        }

        return $this->save(false);
    }




}