<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/3/7
 * Time : 10:56
 */
use app\components\ViewHelper;
?>
<!-- 广告 -->
<div class="widget widget-gallery-sm">
    <h4 class="widget-title text-left"><?= $title?></h4>
    <a href="#">
        <img src="<?= ViewHelper::showImage($image)?>" alt="">
    </a>
</div>
