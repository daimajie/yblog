<?php

namespace app\models\rbac;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%auth_rule}}".
 *
 * @property string $name
 * @property resource $data
 * @property int $created_at
 * @property int $updated_at
 *
 * @property AuthItem[] $authItems
 */
class AuthRule extends \yii\db\ActiveRecord
{
    public $rulePath;

    public $_auth;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%auth_rule}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rulePath'], 'required'],
            [['rulePath'], 'string', 'max' => 125],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => '规则名称',
            'data' => '数据',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
            'rulePath' => '规则全路径'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItems()
    {
        return $this->hasMany(AuthItem::className(), ['rule_name' => 'name']);
    }

    public function getAuthManager(){
        if(empty($this->_auth)){
            $this->_auth = Yii::$app->authManager;
        }
        return $this->_auth;

    }

    //保存规则
    public function store(){
        //验证数据
        if(!$this->validate())
            return false;

        //类是否存在
        if(!class_exists($this->rulePath)){
            $this->addError('rulePath', '类文件不存在。');
            return false;
        }

        $auth = $this->getAuthManager();

        //建立规则对象
        $rule = new $this->rulePath;

        //是否已经存在
        if($auth->getRule($rule->name)){
            $this->addError('rulePath', '已经存在同名规则。');
            return false;
        }

        //保存
        return $auth->add($rule);
    }
}

