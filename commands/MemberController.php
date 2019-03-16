<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/3/16
 * Time : 11:21
 */

namespace app\commands;
use app\models\member\User;
use yii\console\Controller;
use Yii;
use yii\console\ExitCode;

class MemberController extends  Controller
{
    private $authManager;

    public function init()
    {
        parent::init();
        $this->authManager = Yii::$app->authManager;
    }

    //清空所有角色指派
    public function actionRemove(){
        //先清空所有权限分配
        $this->authManager->removeAllAssignments();
        //删除所有用户
        User::deleteAll();
        echo "已清空用户及角色指派!\n";

        return ExitCode::OK;

    }

    //添加管理员
    public function actionAddAdmin(){
        try{
            if(!$user = $this->addMember())
                throw new \Exception('创建管理员失败，请重试.');

            // 分配角色：
            $auth = $this->authManager;
            if(!$adminRole = $auth->getRole('管理员') && !$authorRole = $auth->getRole('作者')){
                throw new \Exception('请先执行Rbac/init 添加权限和角色。.');
            }

            $auth->assign($adminRole, $user->getId());
            $auth->assign($authorRole, $user->getId());
            echo "创建管理员成功!\n";

            return ExitCode::OK;
        }catch (\Exception $e){

            echo $e->getMessage()."\n";
            return ExitCode::UNSPECIFIED_ERROR;
        }
    }

    //添加作者
    public function actionAddAuthor(){
        try{
            if(!$user = $this->addMember())
                throw new \Exception('创建作者失败，请重试。');

            // 要添加以下三行代码：
            $auth = Yii::$app->authManager;
            if(!$authorRole = $auth->getRole('作者')){
                throw new \Exception('请先执行Rbac/init 添加权限和角色。.');
            }

            $auth->assign($authorRole, $user->getId());
            echo "创建作者成功!\n";

            return ExitCode::OK;
        } catch (\Exception $e){
            echo $e->getMessage() . "\n";
            return ExitCode::UNSPECIFIED_ERROR;
        }
    }



    /**
     * 获取输入信息
     */
    private function addMember(){
        $username = $this->prompt('username: ', ['require' => true, 'validator' => function($input, &$error) {
            if (strlen($input) < 5) {
                $error = 'The Pin must be Greater than or equal to 5 chars!';
                return false;
            }
            return true;
        }]);
        $password = $this->prompt('password: ', ['require' => true,'validator' => function($input, &$error) {
            if (strlen($input) < 6) {
                $error = 'The Pin must be Greater than or equal to 6 chars!';
                return false;
            }
            return true;
        }]);
        $email = $this->prompt('email: ', ['require' => true, 'validator' => function($input, &$error) {
            if (!filter_var($input, FILTER_VALIDATE_EMAIL))
            {
                $error = 'The Pin must be email!';
                return false;
            }
            return true;
        }]);
        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->author = 0;//读者为-1 作者为0+
        $user->generatePasswordHash($password);
        $user->generateAuthKey();
        //保存数据
        if(!$user->save(false)){
            //throw new \Exception('create manger fail。');
            return false;
        }
        return $user;
    }

}