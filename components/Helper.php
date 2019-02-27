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
use yii\helpers\Html;


class Helper
{

    /**
     * 显示用户头像
     */
    public static function avatar($img){
        if(empty($img))
            return Yii::$app->params['user_properties']['defaultAvatar'];

        return self::showImage($img);
    }

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

    /**
     * #删除图片
     * @param $img string #图片保存路径
     * @return bool
     */
    public static function delImage($img){
        if(empty($img)) return true;

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

    /**
     * 截取指定长度字符串
     * @param $string string #要截取的字符串
     * @param $length int #截取长度
     * @param string $etc #追加符号
     * @return string #截取后的字符串
     */
    public static function truncate_utf8_string($string, $length, $etc = '...')
    {
        $result = '';
        $string = html_entity_decode(trim(strip_tags($string)), ENT_QUOTES, 'UTF-8');
        $strlen = strlen($string);
        for ($i = 0; (($i < $strlen) && ($length > 0)); $i++)
        {
            if ($number = strpos(str_pad(decbin(ord(substr($string, $i, 1))), 8, '0', STR_PAD_LEFT), '0'))
            {
                if ($length < 1.0)
                {
                    break;
                }
                $result .= substr($string, $i, $number);
                $length -= 1.0;
                $i += $number - 1;
            }
            else
            {
                $result .= substr($string, $i, 1);
                $length -= 0.5;
            }
        }
        $result = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');
        if ($i < $strlen)
        {
            $result .= $etc;
        }
        return $result;
    }

    /**
     * 限速功能
     * @param string $key session 键
     * @param int $num 单位时间内发送条数
     * @param int $term 单位时间
     * @return bool
     */
    public static function setLimit($key, $num = 5, $term = 900){
        $session = Yii::$app->getSession();
        if( !$session->has($key) ){
            $arr = [
                'start' => time(),
                'count' => 0,
                'term' => $term
            ];
            $session->set($key, $arr);
        }

        $data = $session[$key];

        //判断在一定时间内是否超过限制
        if($data['count'] >= $num && ( time() - $data['start'] ) < $data['term']){
            return false;
        }

        //重新记录
        if( $data['count'] >= $num ){
            $data['start'] = time();
            $data['count'] = 0;
        }
        $data['count']++; //发送一次递增一次
        $session[$key] = $data;//保存回session
        return true;
    }
}