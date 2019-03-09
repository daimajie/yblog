<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/3/8
 * Time : 11:31
 */

namespace app\components\behaviors;
use app\components\Helper;
use Yii;
use yii\web\ForbiddenHttpException;
use app\models\content\Article;

class AccessControl extends \yii\base\ActionFilter
{
    /**
     *  对用户请求的路由进行验证
     *  @return true 表示有权访问
     */
    public function beforeAction ($action)
    {

        // 当前路由
        $actionId = $action->getUniqueId();
        $actionId = '/' . $actionId;


        // 当前登录用户的id
        $user = Yii::$app->getUser();


        //判断是否是作者
        if(!$user->isGuest && $user->identity->author >= 0){

            if (Helper::checkRoute($actionId, Yii::$app->getRequest()->get(), $user)) {
                return true;
            }
        }

        $this->denyAccess($user);
        return false;
    }

    /**
     *  拒绝用户访问
     *  访客，跳转去登录；已登录，抛出403
     *  @param $user 当前用户
     *  @throws ForbiddenHttpException 如果用户已经登录，抛出403.
     */
    protected function denyAccess($user)
    {
        if ($user->getIsGuest()) {
            $user->loginRequired();
        } else {
            Yii::$app->errorHandler->errorAction = '/home/index/error';
            throw new ForbiddenHttpException('不允许访问.');
        }
    }
}