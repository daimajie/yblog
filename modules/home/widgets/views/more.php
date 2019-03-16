<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/3/6
 * Time : 11:28
 */
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\ViewHelper;
?>
<section class="section mb-20">
    <div class="title-wrap">
        <h3 class="section-title section-title--sm">热门文章</h3>
        <div class="carousel-nav">
            <button class="carousel-nav__btn carousel-nav__btn--prev" aria-label="previous slide">
                <i class="ui-arrow-left"></i>
            </button>
            <button class="carousel-nav__btn carousel-nav__btn--next" aria-label="next slide">
                <i class="ui-arrow-right"></i>
            </button>
        </div>
    </div>

    <!-- Slider -->
    <div id="owl-posts" class="owl-carousel owl-theme">

        <?php
        foreach($articles as $key => $item):
        ?>
            <article class="entry">
                <div class="entry__img-holder">
                    <a href="<?= Url::to(['/home/content/article/view','id'=>$item['id']])?>">
                        <div class="thumb-container thumb-75">
                            <img data-src="<?= ViewHelper::showImage($item['image'])?>" src="<?= ViewHelper::showImage($item['image'])?>" class="entry__img owl-lazy" alt="" />
                        </div>
                    </a>
                </div>

                <div class="entry__body">
                    <div class="entry__header">
                        <h2 class="entry__title entry__title--sm">
                            <a href="<?= Url::to(['/home/content/article/view','id'=>$item['id']])?>"><?= Html::encode($item['title'])?></a>
                        </h2>
                        <ul class="entry__meta">
                            <li class="entry__meta-date">
                                <span>最后评论 </span>
                                <?= ViewHelper::time($item['updated_at'])?>
                            </li>
                            <li class="entry__meta-comments">
                                <i class="ui-comments"></i>
                                <a href="javascript:void(0);"><?= $item['comment']?></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </article>
        <?php
        endforeach;
        if(empty($articles)) echo '暂无数据。';
        ?>

    </div>
    <!-- end slider -->
</section>
