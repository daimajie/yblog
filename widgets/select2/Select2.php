<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/1/24
 * Time : 13:10
 */

namespace app\widgets\select2;

use yii\base\Exception;
use yii\base\InvalidArgumentException;
use yii\bootstrap\InputWidget;
use app\widgets\select2\assets\Select2Asset;
use yii\helpers\Json;


class Select2 extends InputWidget
{
    public $searchAfterJsFun; //下拉菜单改变后触发的js回调函数名称
    public $selected; //选中的一个项目
    public $width;
    public $selectUrl;

    public function init()
    {
        parent::init();

        if(empty($this->selectUrl))
            throw new Exception('传递参数(selectUrl)错误。');

        $this->searchAfterJsFun = !empty($this->searchAfterJsFun) ? $this->searchAfterJsFun : 'function(){return;}';
        $this->selected = !empty($this->selected) ? $this->selected : [];
        $this->width = !empty($this->width) ? $this->width : '100%';
        // 注册客户端所需要的资源
        $this->registerAssets();
    }

    public function run()
    {
        // 构建html结构
        if ($this->hasModel()) {
            //输出html
            return $this->render('select2',[
                'model' => $this->model,
                'id' => $this->options['id'],
                'searchAfterJsFun' => $this->searchAfterJsFun,
                'selected' => Json::encode($this->selected),
                'width' => $this->width,
                'selectUrl' => $this->selectUrl
            ]);
        } else {
            throw new InvalidArgumentException("'model' must be specified.");
        }

    }

    //注册资源
    public function registerAssets(){

        $view = $this->getView();
        Select2Asset::register($view);
    }
}