<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/2/13
 * Time : 16:25
 */

namespace app\models\member;


class UserForm extends User
{
    //场景
    const SCENARIO_ADD = 'add';
    const SCENARIO_PUT = 'put';

    const SCENARIO_STATUS = 'status';

    //属性
    public $password;
    public $re_password;

    //规则
    public function rules()
    {
        return array_merge(parent::rules(),[
            [['password'], 'required', 'on'=>[self::SCENARIO_ADD]],

            [['status', 'author'], 'required', 'on'=>[self::SCENARIO_STATUS]],
            [['status'], 'integer', 'on'=>[self::SCENARIO_STATUS]],
            [['author'], 'in','range' => [-1, 0], 'on'=>[self::SCENARIO_STATUS]],


            [['password'], 'string', 'length' => [6, 18]],
            [['re_password'], 'compare', 'compareAttribute' => 'password']
        ]);
    }

    public function scenarios()
    {
        return array_merge(parent::scenarios(),[
            self::SCENARIO_ADD => ['username','nickname','email','password','re_password'],
            self::SCENARIO_PUT => ['username','nickname','email','password','re_password'],
            self::SCENARIO_STATUS => ['status','author'],
        ]);
    }

    //label
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(),[
            'password' => '密码',
            're_password' => '重复密码'
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