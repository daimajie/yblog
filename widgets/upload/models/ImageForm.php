<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/1/21
 * Time : 10:34
 */

namespace app\widgets\upload\models;


use yii\base\Model;
use app\components\Helper;
use yii\imagine\Image;

class ImageForm extends Model
{
    public $image;
    public $fullName;

    public function rules()
    {
        return [
            ['image', 'required'],
            ['image', 'image', 'extensions' => 'png, jpg, gif, jpeg',
                'minWidth' => 100, 'maxWidth' => 1000,
                'minHeight' => 100, 'maxHeight' => 1000,
            ],
        ];
    }


    /**
     * #图片上传
     * @param $subDir string #子目录
     */
    public function upload(){

        //验证数据
        if ( !$this->validate() ){
            return false;
        }

        $ext = $this->image->extension;
        $name = $this->image->baseName;

        //上传
        $tmpPath = Helper::getTmpUploadPath();
        $tmpFullPath = $this->fullName = $tmpPath . '/' . $name . '.' . $ext;

        $ret = $this->image->saveAs($tmpFullPath);

        if( !$ret ){
            $this->addError('image', '图片保存失败，请重试。');
            return false;
        }

        //返回上传文件全路径
        return true;

    }


    public function thumb($dir, $width, $height){
        $pathInfo = Helper::generateUploadPath($dir, $this->image->extension);

        Image::thumbnail($this->fullName, $width, $height)
            ->save($pathInfo['absPath'], ['quality' => 50]);

        //3.删除原图
        Helper::deleteFile($this->fullName);

        return $pathInfo;
    }
}