<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/3/7
 * Time : 12:59
 */

namespace app\modules\home\modules\write\controllers;


use app\modules\home\controllers\BaseController;
use Yii;
use yii\web\ForbiddenHttpException;

class WriteBaseController extends BaseController
{
    public function behaviors()
    {
        return [
            'as access-control' => [
                'class' => 'app\components\behaviors\AccessControl',
            ],
        ];
    }

}