<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/3/15
 * Time : 14:29
 */

namespace app\modules\admin\modules\setting\controllers;

use app\modules\home\controllers\BaseController as Base;
use app\modules\admin\controllers\BaseController;
use Yii;
use app\modules\admin\controllers\SiteController;

class CacheController extends BaseController
{
    public $cache;
    public function init()
    {
        parent::init();
        $this->cache = Yii::$app->cache;
    }

    //缓存列表
    public function actionIndex(){
        $caches = [];
        //seo
        $caches[Base::CACHE_CATEGORY_LIST]['exists'] = $this->cache->exists(Base::CACHE_CATEGORY_LIST);
        $caches[Base::CACHE_CATEGORY_LIST]['desc'] = '导航中的分类列表缓存")';
        //轮播图
        $caches[Base::CACHE_SEO]['exists'] = $this->cache->exists(Base::CACHE_SEO);
        $caches[Base::CACHE_SEO]['desc'] = '网站名称 关键字 和描述 关于我 等seo信息缓存';
        //控制台统计缓存
        $caches[SiteController::CACHE_COUNT]['exists'] = $this->cache->exists(SiteController::CACHE_COUNT);
        $caches[SiteController::CACHE_COUNT]['desc'] = '控制台首页统计数据缓存';



        return $this->render('index',[
            'caches' => $caches,
        ]);
    }

    //清除所有缓存
    public function actionFlush(){
        $this->cache->flush();
        return $this->redirect(['index']);
    }
    //清空seo缓存
    public function actionSeo(){
        $this->cache->delete(Base::CACHE_SEO);
        return $this->redirect(['index']);
    }
    //清空侧边栏缓存
    public function actionCategory_list(){
        $this->cache->delete(Base::CACHE_CATEGORY_LIST);
        return $this->redirect(['index']);
    }

    //清空统计缓存
    public function actionCount(){
        $this->cache->delete(SiteController::CACHE_COUNT);
        return $this->redirect(['index']);
    }

}