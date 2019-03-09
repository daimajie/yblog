<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/1/21
 * Time : 11:19
 */

namespace app\widgets\upload;

use yii\base\InvalidConfigException;
use yii\bootstrap\InputWidget;
use app\widgets\upload\assets\UploadAsset;

class Upload extends InputWidget
{
    public $name;
    public $info;
    public $thumb = [];
    public $show = true;

    public $uploadPath = 'upload';



    public function init()
    {
        parent::init();
        //初始化数据
        $this->name = isset($this->name) ? $this->name: 'image';
        $this->info = isset($this->info) ? $this->info: '选择一张图片作为话题封面。';


        if( !is_array($this->thumb) ||
            !isset($this->thumb['width']) ||
            !isset($this->thumb['height'])
        ){

            $this->thumb = [
                'width' => 370,
                'height' => 238
            ];
        }

        // 注册客户端所需要的资源
        $this->registerAssets();
    }

    public function run()
    {

        // 构建html结构
        if ($this->hasModel()) {
            //输出html
            return $this->render('upload',[
                'model' => $this->model,
                'name' => $this->name,
                'info' => $this->info,
                'thumb' => $this->thumb,
                'id' => $this->options['id'],
                'show' => $this->show,
                'uploadPath' => $this->uploadPath
            ]);
        } else {
            throw new InvalidConfigException("'model' must be specified.");
        }


    }

    //注册webupload插件
    public function registerAssets(){

        $view = $this->getView();
        UploadAsset::register($view);
    }
}