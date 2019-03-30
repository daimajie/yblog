<?php
namespace app\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * Alert widget renders a message from session flash. All flash messages are displayed
 * in the sequence they were assigned using setFlash. You can set message as following:
 *
 * ```php
 * Yii::$app->session->setFlash('error', 'This is the message');
 * Yii::$app->session->setFlash('success', 'This is the message');
 * Yii::$app->session->setFlash('info', 'This is the message');
 * ```
 *
 * Multiple messages could be set as follows:
 *
 * ```php
 * Yii::$app->session->setFlash('error', ['Error 1', 'Error 2']);
 * ```
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @author Alexander Makarov <sam@rmcreative.ru>
 */
class HomeAlert extends Widget
{
    /**
     * @var array the alert types configuration for the flash messages.
     * This array is setup as $key => $value, where:
     * - key: the name of the session flash variable
     * - value: the bootstrap alert type (i.e. danger, success, info, warning)
     */
    public $alertTypes = [
        'error'   => 'alert-danger',
        'danger'  => 'alert-danger',
        'success' => 'alert-success',
        'info'    => 'alert-info',
        'warning' => 'alert-warning'
    ];

    public $options = [];



    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $session = Yii::$app->session;
        $flashes = $session->getAllFlashes();
        $appendClass = isset($this->options['class']) ? ' ' . $this->options['class'] : '';

        foreach ($flashes as $type => $flash) {
            if (!isset($this->alertTypes[$type])) {
                continue;
            }

            foreach ((array) $flash as $i => $message) {
                //生成元素
                echo $this->bannerBar($message, $type, $appendClass);

            }

            $session->removeFlash($type);
        }
    }

    private function closeBtn(){
        return Html::button('<span aria-hidden="true">&times;</span>', [
            'class' => 'close',
            'data-dismiss' => 'alert',
            'aria-label' => 'Close'
        ]);
    }

    private function bannerBar($message, $type, $appendClass){
        return Html::tag('div',$message . $this->closeBtn(),[
           'class' => 'alert alert-dismissible fade show col-lg-12 fade in ' . $this->alertTypes[$type] . $appendClass,
           'role' => 'alert'
        ]);
    }


}
