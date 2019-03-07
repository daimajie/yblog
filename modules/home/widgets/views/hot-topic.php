<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/3/6
 * Time : 12:18
 */
use yii\helpers\Url;
use yii\helpers\Html;
use app\components\ViewHelper;
?>
<!-- 热门话题 -->
<div class="widget widget-gallery-sm">
    <h4 class="widget-title text-left">热门话题</h4>
    <ul class="widget-gallery-sm__list" style="justify-content:flex-start">
        <?php
        foreach($topics as $key => $item):
        ?>
            <li class="widget-gallery-sm__item">
                <a href="<?= Url::to(['/home/content/topic/view','id'=>$item['id']])?>">
                    <img width="125" src="<?= ViewHelper::showImage($item['image'])?>" alt="<?= Html::encode($item['name'])?>">
                </a>
            </li>
        <?php
        endforeach;
        if(empty($topics)) echo '暂无数据。';
        ?>
    </ul>
</div>
