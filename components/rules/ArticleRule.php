<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/3/8
 * Time : 15:03
 */

namespace app\components\rules;
use yii\rbac\Rule;
use app\models\content\Article;

class ArticleRule extends Rule
{
    public $name = 'article';

    public function execute($user, $item, $params)
    {
        if(empty($params['id'])) return false;

        return (Article::findOne($params['id']))->user_id == $user;//是否是创建者
    }
}