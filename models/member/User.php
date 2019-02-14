<?php

namespace app\models\member;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property string $id 主键
 * @property string $username 用户名
 * @property string $nickname 昵称
 * @property string $email 邮箱
 * @property string $image 头像
 * @property int $status 状态
 * @property int $author 文章数目：-1非作者
 * @property string $auth_key Auth_Key
 * @property string $password_hash 密码
 * @property string $password_reset_token 密码重置key
 * @property string $created_at 注册时间
 * @property string $updated_at 修改时间
 */
class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'nickname',
                ],
                'value' => function ($event) {
                    return '_' . $this->username;
                },
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username','email'], 'required'],
            [['status', 'author', 'created_at', 'updated_at'], 'integer'],
            [['username', 'nickname'], 'string', 'max' => 18],
            [['email', 'image'], 'string', 'max' => 64],
            [['auth_key', 'password_hash', 'password_reset_token'], 'string', 'max' => 255],
            [['username'], 'unique'],
            [['nickname'], 'unique'],
            [['email'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'username' => '用户名',
            'nickname' => '昵称',
            'email' => '邮箱',
            'image' => '头像',
            'status' => '状态',
            'author' => '文章数目：-1非作者',
            'auth_key' => 'Auth_Key',
            'password_hash' => '密码',
            'password_reset_token' => '密码重置key',
            'created_at' => '注册时间',
            'updated_at' => '修改时间',
        ];
    }

    //生成密码
    public function generatePasswordHash($password){
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    //生成auth_key
    public function generateAuthKey(){
        $this->auth_key = Yii::$app->security->generateRandomString();
    }


    /****/
    //生成reset token
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    //根据用户名或邮箱获取用户实例
    public static function findByUsernameOrEmail($username){
        $model = self::find()->where(['or', 'username=:user', 'email=:user'], [':user' => $username])->one();
        if (!$model)
            return null;
        return $model;
    }

    //验证用户名和邮箱是否匹配
    public static function findByUsernameAndEmail($username, $email){
        $model = self::find()->where([
            'and',
            'username=:username',
            'email=:email'
        ], [
            ':username' => $username,
            ':email' => $email
        ])->one();
        if (!$model)
            return null;
        return $model;
    }

    //根据reset password token 获取用户
    public static function findByPasswordResetToken($token){
        if (empty($token)) {
            return null;
        }
        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['passwordResetTokenExpire'];
        if( $timestamp + $expire < time() ){
            return null;
        }
        return static::find()->where(['password_reset_token' => $token])->limit(1)->one();
    }
    //验证密码是否正确
    public function validatePassword($password){
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
    public function validateAuthKey($authKey){
        $ret = $this->getAuthKey() === $authKey;
        if($ret){
            //更新最后登录时间
            $this->lasttime = time();
            return $this->save(false);
        }
        return false;
    }
    public function getAuthKey(){
        return $this->auth_key;
    }
    public function getId(){
        return $this->id;
    }
    public static function findIdentityByAccessToken($token, $type = null){
        return static::findOne(['access_token' => $token]);
    }
    public static function findIdentity($id){
        return static::findOne($id);
    }



}
