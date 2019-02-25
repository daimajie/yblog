<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/2/13
 * Time : 22:08
 */

namespace app\modules\admin\controllers;



/**
 * 公共控制器
 * Class SiteController
 * @package app\modules\admin\controllers
 */
class SiteController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex(){
        echo 'admin index';
    }
}