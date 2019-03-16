<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/2/14
 * Time : 22:32
 */

namespace app\modules\home\controllers;

use app\models\content\Article;
use yii\helpers\HtmlPurifier;
use app\models\content\Category;
use app\models\setting\SEO;
use yii\caching\DbDependency;
use yii\helpers\VarDumper;
use yii\web\Controller;
use Yii;

class BaseController extends Controller
{
    const CACHE_CATEGORY_LIST = 'category_list';
    const CACHE_SEO = 'seo';
    const CACHE_LATELY_COMMENT = 'lately';


    public function beforeAction($action)
    {
        if(!parent::beforeAction($action)){
            return false;
        }

        //获取前台公共数据
        $cache = Yii::$app->cache;
        $base = [];

        //**获取分类列表
        $category = $cache->get(static::CACHE_CATEGORY_LIST);
        if($category === false){
            $category = Category::dropItems();

            //设置缓存
            /*$dependency = new DbDependency([
                'sql' => "SELECT MAX(updated_at) FROM {{%category}};"
            ]);*/
            $cache->set(static::CACHE_CATEGORY_LIST, $category, 3600*24/*, $dependency*/);
        }
        $base[static::CACHE_CATEGORY_LIST] = $category;


        //**缓存seo信息(需要保存至数据库)
        $seo = $cache->get(static::CACHE_SEO);
        if ($seo === false) {
            $seo_data = SEO::find()->limit(1)->asArray()->one();
            if(!$seo_data){
                $seo['name'] = Yii::$app->name;
                $seo['keywords'] = '';
                $seo['description'] = '';
                $seo['pc_logo'] = '';
                $seo['mobile_logo'] = '';
                $seo['qrcode'] = '';
            }else{
                $seo = $seo_data;
            }
            $seo['about'] = isset($seo['about'])?HtmlPurifier::process($seo['about']):'';

            //设置缓存
           /* $dependency = new DbDependency([
                'sql' => "SELECT updated_at FROM {{%seo}};"
            ]);*/

            $cache->set(static::CACHE_SEO, $seo, 3600*24/*, $dependency*/);
        }
        $base[static::CACHE_SEO] = $seo;

        //**最新回复文章
        $lately = $cache->get(static::CACHE_LATELY_COMMENT);
        if ($lately === false) {
            $lately = Article::getLately();

            //设置缓存
            /* $dependency = new DbDependency([
                 'sql' => "SELECT updated_at FROM {{%seo}};"
             ]);*/

            $cache->set(static::CACHE_LATELY_COMMENT, $lately, 3600*24/*, $dependency*/);
        }
        $base[static::CACHE_LATELY_COMMENT] = $lately;
        //VarDumper::dump(SEO::find()->limit(1)->asArray()->one(), 10,1);die;




        //分配到试图
        $this->view->params['base'] = $base;
        return true;
    }
}