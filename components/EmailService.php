<?php
/**
 * Created by PhpStorm.
 * User: daimajie
 * Date: 2018/9/26
 * Time: 19:46
 */
namespace app\components;
use yii\base\Exception;
use Yii;
class EmailService extends \yii\base\BaseObject
{
    /**
     * 发送邮件
     * @params $from string #发送邮件的邮箱
     * @params $emails string|array #接收邮件的邮箱
     * @params $subject string #邮件主题
     * @params $view string #使用的视图文件
     * @params $var array #视图变量
     * @return bool #发送成功返回true 否则返回false
     * @throws Exception
     */
    public static function sendEmail($from, $emails, $subject, $view, $var=[]){

        //如果不是一个邮箱数组 也不是一个邮箱
        if(empty($from) ||  empty($emails)){
            throw new Exception('发送邮箱与接收邮箱不能为空。');
        }
        //多封邮件
        if(is_array($emails)){
            $messages = [];
            foreach ($emails as $email) {
                if(!filter_var($email, FILTER_VALIDATE_EMAIL))
                    continue;
                $messages[] = static::createMessage($from, $emails, $subject, $view, $var);
            }
            //发送邮件
            return (int) Yii::$app->mailer->sendMultiple($messages);
        }

        //单封邮件
        if(filter_var($emails, FILTER_VALIDATE_EMAIL)){
            $ret = static::createMessage($from, $emails, $subject, $view, $var);

            return (bool) $ret->send();
        }
        return false;
    }
    /**
     * 创建邮件消息实体
     * @param $from
     * @param $emails
     * @param $subject
     * @param $view
     * @param $var
     * @return \yii\mail\MessageInterface
     */
    private static function createMessage($from, $emails, $subject, $view, $var){
        return Yii::$app->mailer->compose($view,$var)
            ->setFrom($from)
            ->setTo($emails)
            ->setSubject($subject);
    }
    /**
     * 生成验证码
     * @params $len 验证码长度
     * @params $expire 过期时间（单位分钟）
     * @params $key 名字
     * @return string 验证码字符串
     */
    public static function generateCaptcha($len, $expire, $key = 'captcha'){
        if($len <=0 || $len > 18) $len=6;
        $temStr = substr(uniqid(), -$len);
        //保存至session中
        $session = Yii::$app->session;
        if (!$session->isActive)
            $session->open();
        $session[$key] = [
            'captcha' => $temStr,
            'lifetime' => $expire * 60,
            'start_at' => time()
        ];
        return $temStr;
    }
    /**
     * 发送邮件限速功能
     * @param int $num 单位时间内发送条数
     * @param int $term 单位时间
     * @return bool
     */
    public static function sendLimit($num = 5, $term = 900){
        $session = Yii::$app->getSession();
        if( !$session->has('send-limit') ){
            $arr = [
                'start' => time(),
                'count' => 0,
                'term' => $term
            ];
            $session->set('send-limit', $arr);
        }
        $send_limit = $session['send-limit'];
        //判断在一定时间内是否超过限制
        if($send_limit['count'] >= $num && ( time() - $send_limit['start'] ) < $send_limit['term']){
            return false;
        }
        if( $send_limit['count'] >= $num ){
            $send_limit['start'] = time();
            $send_limit['count'] = 0;
        }
        $send_limit['count']++; //发送一次递增一次
        $session['send-limit'] = $send_limit;//保存会session
        return true;
    }
}