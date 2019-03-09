<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/1/20
 * Time : 18:47
 */

namespace app\modules\admin\controllers;


use yii\web\Controller;
use Yii;
use yii\web\ForbiddenHttpException;

class BaseController extends Controller
{
    public function behaviors()
    {
        return [
            /*'as access-control' => [
                'class' => 'app\components\behaviors\AccessControl',
            ],*/
        ];
    }

}