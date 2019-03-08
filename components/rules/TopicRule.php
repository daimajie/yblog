<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/3/7
 * Time : 23:14
 */

namespace app\components\rules;
use yii\rbac\Rule;
use app\models\content\Topic;

class TopicRule extends Rule
{
    public $name = 'topic';

    public function execute($user, $item, $params)
    {
        if(empty($params['id'])) return true;

        return (Topic::findOne($params['id']))->user_id == $user;//是否是创建者
    }
}