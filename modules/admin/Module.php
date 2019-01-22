<?php

namespace app\modules\admin;
use Yii;

/**
 * admin module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\admin\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        $this->config();
    }

    private function config(){
        //模块名称
        Yii::$app->name = Yii::$app->params['admin_app']['name'];

        //模块配置
        Yii::configure($this, require __DIR__ . '/config.php');

        //模块资源管理设置
        Yii::$app->assetManager->bundles = [
            'dmstr\web\AdminLteAsset' => [
                'skin' => 'skin-blue',
            ]
        ];


    }


}
