<?php
/*
namespace app\models\rbac;

use Yii;
use yii\behaviors\TimestampBehavior;


class AuthItem extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return '{{%auth_item}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }


    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['type', 'created_at', 'updated_at'], 'integer'],
            [['description', 'data'], 'string'],
            [['name', 'rule_name'], 'string', 'max' => 64],
            [['name'], 'unique'],
            [['rule_name'], 'exist', 'skipOnError' => true, 'targetClass' => AuthRule::className(), 'targetAttribute' => ['rule_name' => 'name']],
        ];
    }


    public function attributeLabels()
    {
        return [
            'name' => '权限名称',
            'type' => '类型',
            'description' => '描述',
            'rule_name' => '规则名称',
            'data' => '附加数据',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }


    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::className(), ['item_name' => 'name']);
    }


    public function getRuleName()
    {
        return $this->hasOne(AuthRule::className(), ['name' => 'rule_name']);
    }


    public function getAuthItemChildren()
    {
        return $this->hasMany(AuthItemChild::className(), ['parent' => 'name']);
    }


    public function getAuthItemChildren0()
    {
        return $this->hasMany(AuthItemChild::className(), ['child' => 'name']);
    }

    public function getChildren()
    {
        return $this->hasMany(AuthItem::className(), ['name' => 'child'])->viaTable('{{%auth_item_child}}', ['parent' => 'name']);
    }


    public function getParents()
    {
        return $this->hasMany(AuthItem::className(), ['name' => 'parent'])->viaTable('{{%auth_item_child}}', ['child' => 'name']);
    }
}*/

namespace app\models\rbac;

use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\helpers\VarDumper;


class AuthItem extends \yii\db\ActiveRecord
{
    const TYPE_ROLE = 1; //角色
    const TYPE_ROUTER = 2;//路由

    private $_auth;

    public static function tableName()
    {
        return '{{%auth_item}}';
    }

    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['type'], 'in', 'range' => [1,2], 'message' => '请选择正确的类型'],
            [['description', 'data'], 'string'],
            [['name', 'rule_name'], 'string', 'max' => 64],
            [['name'], 'unique'],
            [['rule_name'], 'exist', 'skipOnError' => true, 'targetClass' => AuthRule::className(), 'targetAttribute' => ['rule_name' => 'name']],
        ];
    }


    public function attributeLabels()
    {
        return [
            'name' => '项目名称',
            'type' => '项目类型',
            'description' => '描述',
            'rule_name' => '规则名称',
            'data' => '附加数据',
            'created_at' => '创建时间',
            'updated_at' => '修改时间'
        ];
    }

    //新建修改权限项目
    public function store(){
        //验证数据
        if(!$this->validate())
            return false;

        $auth = $this->getAuthManager();

        //删除原有项目
        if( !$this->isNewRecord ){
            //原来的类型和名字
            $type = $this->getDirtyAttributes(['type']) ? $this->getOldAttribute('type') : $this->type;
            $name = $this->getDirtyAttributes(['name']) ? $this->getOldAttribute('name') : $this->name;

            if($type == self::TYPE_ROUTER){
                $item = $auth->getPermission($name);
            }else{
                $item = $auth->createRole($name);
            }
            $auth->remove($item);
        }

        if($this->type == self::TYPE_ROUTER){
            $item = $auth->createPermission($this->name);
        }else{
            $item = $auth->createRole($this->name);
        }


        $item->description = $this->description;
        if(!empty($this->rule_name)){
            //添加规则
            $rule = $auth->getRule($this->rule_name);
            if(!$rule){
                $this->addError('rule_name', '您指定的规则不存在。');
                return false;
            }
            $item->ruleName = $rule->name;
        }
        if(!empty($this->data)){
            //添加附加数据
            $item->data = $this->data;
        }

        try{
            $auth->add($item);

        }catch (\Exception  $e){
            Yii::error($e->getMessage(), __METHOD__);
            $this->addError('name', $e->getMessage());
            return false;
        }
        return true;
    }

    //删除
    public static function delItem($id){
        $auth = Yii::$app->getAuthManager();

        $item = $auth->getPermission($id);
        if(!$item){
            $item = $auth->getRole($id);
        }

        if(!$item){
            throw new Exception('传递参数错误。');
        }

        return $auth->remove($item);
    }

    //为角色添加路由
    public function addChildren($params){
        //检测参数
        if(empty($params['role_name'])){
            throw new Exception('传递角色参数错误。');
        }

        //auth
        $auth = $this->getAuthManager();
        $role = $auth->getRole($params['role_name']);

        if(!$role){
            throw new Exception('传递角色参数错误。');
        }

        //1.清空所有路由
        $auth->removeChildren($role);


        //2.遍历路由并添加到角色
        if(!empty($params['children'])){
            foreach($params['children'] as $key => $child){
                $router = $auth->getPermission($child);

                if($auth->canAddChild($role, $router)){

                    $auth->addChild($role, $router);
                }
            }
        }
        return true;
    }


    //获取authManager
    private function getAuthManager(){
        if(empty($this->_auth))
            $this->_auth = Yii::$app->authManager;
        return $this->_auth;
    }




    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::className(), ['item_name' => 'name']);
    }


    public function getRuleName()
    {
        return $this->hasOne(AuthRule::className(), ['name' => 'rule_name']);
    }


    public function getAuthItemChildren()
    {
        return $this->hasMany(AuthItemChild::className(), ['parent' => 'name']);
    }


    public function getAuthItemChildren0()
    {
        return $this->hasMany(AuthItemChild::className(), ['child' => 'name']);
    }

    public function getChildren()
    {
        return $this->hasMany(AuthItem::className(), ['name' => 'child'])->viaTable('{{%auth_item_child}}', ['parent' => 'name']);
    }


    public function getParents()
    {
        return $this->hasMany(AuthItem::className(), ['name' => 'parent'])->viaTable('{{%auth_item_child}}', ['child' => 'name']);
    }
}

