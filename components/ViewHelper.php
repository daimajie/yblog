<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/2/19
 * Time : 14:28
 */

namespace app\components;
use Yii;
use yii\helpers\Html;

class ViewHelper
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
     * 显示用户名(账户或昵称)
     */
    public static function username($username, $nickname){
        return empty($nickname)?Html::encode($username):Html::encode($nickname);
    }

    /**
     * 显示图片
     */
    public static function showImage($savePath){
        if(empty($savePath)) return'';
        $upRoot = Yii::$app->params['upload']['upRoot'];

        return $upRoot . '/' .$savePath;
    }

    /**
     * 静态资源
     */
    public static function staticPath($static){
        return Yii::getAlias('@web') . '/static/assets/' . $static;
    }

    /**
     * 显示相对时间
     */
    public static function time($time){
        return Yii::$app->formatter->asRelativeTime($time);
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

}