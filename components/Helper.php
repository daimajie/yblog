<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/1/21
 * Time : 21:50
 */

namespace app\components;
use Yii;
use yii\base\InvalidArgumentException;
use yii\helpers\FileHelper;


class Helper
{
    /**
     * #生成上传路径及随机文件名
     * @param $subDir string #分类目录
     * @param $ext string #文件扩展名
     * @return array #保存路径及文件全路径
     * @throws \yii\base\Exception
     */
    public static function generateUploadPath($subDir, $ext){
        if(empty($subDir) || empty($ext))
            throw new InvalidArgumentException('参数错误。');

        //获取上传根目录
        $upRoot = Yii::$app->params['upload']['upRoot'];

        //$static = Yii::getAlias('@web') .'/'. $upRoot;
        $absolutePath = Yii::getAlias('@webroot') .'/'. $upRoot;

        //生成子目录
        $path = $subDir .'/'. date('Y'). '/' . date('m') . '/' . date('d');

        //创建目录
        if(!is_dir($absolutePath .'/'. $path))
            FileHelper::createDirectory($absolutePath .'/'. $path, 0777, true);

        $name = uniqid($subDir.'_') . time() . '.' . ltrim($ext, '.');

        return [
            //'webUrl' => $static . $path . $name,
            'absPath' => $absolutePath .'/'. $path .'/'. $name,
            'savePath' =>  $path .'/'. $name
        ];

        return [$path, $name];
    }

    /**
     * #获取文件上传临时目录
     * @return string #临时目录
     * @throws \yii\base\Exception
     */
    public static function getTmpUploadPath(){
        $tmpPath = Yii::$app->params['upload']['tmpPath'];
        $path = Yii::getAlias('@webroot') . '/' . $tmpPath;

        if(!is_dir($path)){
            FileHelper::createDirectory($path, 0777, true);
        }

        return $path;
    }

    /**
     * #删除文件
     * @param $file string #文件全路径
     * @return bool
     */
    public static function deleteFile($file){
        if (!is_file($file))
            return true;

        @FileHelper::unlink($file);
        return true;
    }

    public static function delImage($img){
        //获取上传目录
        $upRoot = Yii::getAlias('@webroot') . '/' .Yii::$app->params['upload']['upRoot'];

        $fileFullName = $upRoot . '/' . $img;

        //判断文件是否存在
        if ( !file_exists($fileFullName) )
            return true;

        return self::deleteFile($fileFullName);
    }

    /**
     * 显示图片
     */
    public static function showImage($savePath){
        if(empty($savePath)) throw new InvalidArgumentException('传递参数错误。');
        $upRoot = Yii::$app->params['upload']['upRoot'];

        return $upRoot . '/' .$savePath;
    }
}