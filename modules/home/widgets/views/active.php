<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/3/6
 * Time : 13:37
 */
use app\components\ViewHelper;
use yii\helpers\Url;
?>
<div class="widget widget-gallery-sm">
    <h4 class="widget-title text-left">活跃作者</h4>
    <ul class="widget-gallery-sm__list active-author" style="justify-content:flex-start;">
        <?php
        foreach($authors as $key => $item):
        ?>
            <li class="m-1 widget-gallery-sm__item">
                <a href="<?= Url::to(['/home/member/author/index','id'=>$item['id']])?>">
                    <img width="50" height="50" src="<?= ViewHelper::showImage($item['image'])?>" alt="<?= ViewHelper::username($item['username'], $item['nickname'])?>">
                </a>
            </li>
        <?php
        endforeach;
        ?>
    </ul>
</div>
