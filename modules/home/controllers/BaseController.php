<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/2/14
 * Time : 22:32
 */

namespace app\modules\home\controllers;


use app\models\content\Category;
use yii\web\Controller;
use Yii;

class BaseController extends Controller
{
    const CACHE_CATEGORY_LIST = 'category_list';


    public function beforeAction($action)
    {
        if(!parent::beforeAction($action)){
            return false;
        }

        //获取前台公共数据
        $cache = Yii::$app->cache;
        $base = [];

        //获取分类列表
        $category = $cache->get(static::CACHE_CATEGORY_LIST);
        if($category === false){
            $category = Category::dropItems();
        }
        $base[static::CACHE_CATEGORY_LIST] = $category;




        //分配到试图
        $this->view->params['base'] = $base;
        return true;
    }
}