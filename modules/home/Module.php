<?php

namespace app\modules\home;

use Yii;
/**
 * home module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\home\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        $this->config();
    }

    private function config(){

        //模块配置
        Yii::configure($this, require __DIR__ . '/config.php');

    }

}
