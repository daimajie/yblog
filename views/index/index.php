<?php
use yii\widgets\ListView;
?>
<!-- Content -->
<div class="row">

    <!-- Posts -->
    <div class="col-lg-8 blog__content mb-30">
        <!-- Latest News -->
        <section class="section">
            <div class="title-wrap">
                <h3 class="section-title">最新文章</h3>
            </div>

            <!--文章列表-->
            <?php
            //话题列表
            try{
                echo ListView::widget([
                    'dataProvider' => $dataProvider,
                    'itemView' => '@app/modules/home/modules/content/views/topic/_article',
                    'layout' => "<div class='row mb-30'>{items}</div>{pager}",
                    'viewParams' => [
                            'count' => count($dataProvider->getModels())
                    ],
                    'options' => [
                        'tag' => false
                    ],
                    'itemOptions' => [
                        'tag' => false
                    ],
                    'summaryOptions' => [
                        'class'=>'pull-right'
                    ],
                    'pager' => [
                        'options'=>[
                            'tag'=>'nav',
                            'class' => 'pagination',
                        ],
                        'maxButtonCount' => 5,
                        'linkOptions' =>[
                            'class' => 'pagination__page'
                        ],
                        'linkContainerOptions' => [
                            'tag'=>false
                        ],
                        'disabledListItemSubTagOptions' => [
                            'tag'=>'a',
                            'class'=>'pagination__page pagination__page--current',
                            'href'=>'javascript:void(0)'
                        ],
                        'disableCurrentPageButton' => true,
                        'nextPageLabel' => '<i class="ui-arrow-right"></i>',
                        'prevPageLabel' => '<i class="ui-arrow-left"></i>',
                    ]
                ]);
            }catch (Exception $e){

            }

            ?>

        </section>
        <!-- end latest news -->

        <!-- Carousel posts -->
        <section class="section mb-20">
            <div class="title-wrap">
                <h3 class="section-title section-title--sm">More News</h3>
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
                <article class="entry">
                    <div class="entry__img-holder">
                        <a href="single-post.html">
                            <div class="thumb-container thumb-75">
                                <img data-src="static/assets/img/blog/carousel_img_1.jpg" src="static/assets/img/blog/carousel_img_1.jpg" class="entry__img owl-lazy" alt="" />
                            </div>
                        </a>
                    </div>

                    <div class="entry__body">
                        <div class="entry__header">
                            <h2 class="entry__title entry__title--sm">
                                <a href="single-post.html">The Surprising Way This Designer Picked the Next It Colors</a>
                            </h2>
                            <ul class="entry__meta">
                                <li class="entry__meta-date">
                                    <i class="ui-date"></i>
                                    21 October, 2017
                                </li>
                                <li class="entry__meta-comments">
                                    <i class="ui-comments"></i>
                                    <a href="#">115</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </article>
                <article class="entry">
                    <div class="entry__img-holder">
                        <a href="single-post.html">
                            <div class="thumb-container thumb-75">
                                <img data-src="static/assets/img/blog/carousel_img_2.jpg" src="static/assets/img/blog/carousel_img_2.jpg" class="entry__img owl-lazy" alt="" />
                            </div>
                        </a>
                    </div>

                    <div class="entry__body">
                        <div class="entry__header">
                            <h2 class="entry__title entry__title--sm">
                                <a href="single-post.html">What Fashion Editors Are Buying for Every Kid on Our Holiday List</a>
                            </h2>
                            <ul class="entry__meta">
                                <li class="entry__meta-date">
                                    <i class="ui-date"></i>
                                    21 October, 2017
                                </li>
                                <li class="entry__meta-comments">
                                    <i class="ui-comments"></i>
                                    <a href="#">115</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </article>
                <article class="entry">
                    <div class="entry__img-holder">
                        <a href="single-post.html">
                            <div class="thumb-container thumb-75">
                                <img data-src="static/assets/img/blog/carousel_img_3.jpg" src="static/assets/img/blog/carousel_img_3.jpg" class="entry__img owl-lazy" alt="" />
                            </div>
                        </a>
                    </div>

                    <div class="entry__body">
                        <div class="entry__header">
                            <h2 class="entry__title entry__title--sm">
                                <a href="single-post.html">Why Coach's Cute New Holiday Collab Is Unexpected</a>
                            </h2>
                            <ul class="entry__meta">
                                <li class="entry__meta-date">
                                    <i class="ui-date"></i>
                                    21 October, 2017
                                </li>
                                <li class="entry__meta-comments">
                                    <i class="ui-comments"></i>
                                    <a href="#">115</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </article>
            </div> <!-- end slider -->
        </section>
    </div>
    <!-- end posts -->

    <!-- Sidebar -->
    <aside class="col-lg-4 sidebar sidebar--right">
        <!-- 公众号 -->
        <div class="widget widget_mc4wp_form_widget text-center">
            <img width="100%" src="static/assets/img/blog/rqcode.png" alt="关注微信公众号" class="mb-3">
            <h4 class="widget-title">扫码直接下载APP</h4>
        </div>

        <!-- 社交工具 -->
        <div class="widget widget-social-subscribers">
            <ul class="widget-social-subscribers__list">
                <li class="widget-social-subscribers__item">
                    <a href="#" class="widget-social-subscribers__url widget-social-subscribers--facebook">
                        <i class="ui-facebook widget-social-subscribers__icon"></i>
                        <span class="widget-social-subscribers__number">15369</span>
                        <span class="widget-social-subscribers__text">Fans</span>
                    </a>
                </li>
                <li class="widget-social-subscribers__item">
                    <a href="#" class="widget-social-subscribers__url widget-social-subscribers--twitter">
                        <i class="ui-twitter widget-social-subscribers__icon"></i>
                        <span class="widget-social-subscribers__number">15369</span>
                        <span class="widget-social-subscribers__text">Followers</span>
                    </a>
                </li>
                <li class="widget-social-subscribers__item">
                    <a href="#" class="widget-social-subscribers__url widget-social-subscribers--google">
                        <i class="ui-google widget-social-subscribers__icon"></i>
                        <span class="widget-social-subscribers__number">15369</span>
                        <span class="widget-social-subscribers__text">Followers</span>
                    </a>
                </li>
                <li class="widget-social-subscribers__item">
                    <a href="#" class="widget-social-subscribers__url widget-social-subscribers--rss">
                        <i class="ui-rss widget-social-subscribers__icon"></i>
                        <span class="widget-social-subscribers__number">15369</span>
                        <span class="widget-social-subscribers__text">Subscribers</span>
                    </a>
                </li>
                <li class="widget-social-subscribers__item">
                    <a href="#" class="widget-social-subscribers__url widget-social-subscribers--youtube">
                        <i class="ui-youtube widget-social-subscribers__icon"></i>
                        <span class="widget-social-subscribers__number">15369</span>
                        <span class="widget-social-subscribers__text">Subscribers</span>
                    </a>
                </li>
                <li class="widget-social-subscribers__item">
                    <a href="#" class="widget-social-subscribers__url widget-social-subscribers--instagram">
                        <i class="ui-instagram widget-social-subscribers__icon"></i>
                        <span class="widget-social-subscribers__number">15369</span>
                        <span class="widget-social-subscribers__text">Followers</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- 订阅 -->
        <div class="widget widget_mc4wp_form_widget">
            <h4 class="widget-title">订阅站点动态</h4>
            <form class="mc4wp-form" method="post">
                <div class="mc4wp-form-fields">
                    <p>
                        <input type="email" name="EMAIL" placeholder="你的邮箱" required="">
                    </p>
                    <p>
                        <input type="submit" class="btn btn-lg btn-color" value="提交订阅">
                    </p>
                </div>
            </form>
        </div>

        <!-- 热门文章 -->
        <div class="widget widget-tabpost">
            <div class="tabs widget-tabpost__tabs">
                <ul class="tabs__list widget-tabpost__tabs-list">
                    <li class="tabs__item widget-tabpost__tabs-item tabs__item--active">
                        <a href="#tab-trending" class="tabs__url tabs__trigger widget-tabpost__tabs-url">Trending</a>
                    </li>
                    <li class="tabs__item widget-tabpost__tabs-item">
                        <a href="#tab-latest" class="tabs__url tabs__trigger widget-tabpost__tabs-url">Latest</a>
                    </li>
                    <li class="tabs__item widget-tabpost__tabs-item">
                        <a href="#tab-comments" class="tabs__url tabs__trigger widget-tabpost__tabs-url">Comments</a>
                    </li>
                </ul> <!-- end tabs -->

                <!-- tab content -->
                <div class="tabs__content tabs__content-trigger widget-tabpost__tabs-content">

                    <div class="tabs__content-pane tabs__content-pane--active" id="tab-trending">
                        <ul class="post-list-small">
                            <li class="post-list-small__item">
                                <article class="post-list-small__entry clearfix">
                                    <div class="post-list-small__img-holder">
                                        <div class="thumb-container thumb-75">
                                            <a href="single-post.html">
                                                <img data-src="static/assets/img/blog/popular_post_1.jpg" src="static/assets/img/empty.png" alt="" class=" lazyload">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="post-list-small__body">
                                        <h3 class="post-list-small__entry-title">
                                            <a href="single-post.html">Google is fixing its troubling burger emoji in Android 8.1</a>
                                        </h3>
                                        <ul class="entry__meta">
                                            <li class="entry__meta-date">
                                                <i class="ui-date"></i>
                                                21 October, 2017
                                            </li>
                                        </ul>
                                    </div>
                                </article>
                            </li>
                            <li class="post-list-small__item">
                                <article class="post-list-small__entry clearfix">
                                    <div class="post-list-small__img-holder">
                                        <div class="thumb-container thumb-75">
                                            <a href="single-post.html">
                                                <img data-src="static/assets/img/blog/popular_post_2.jpg" src="static/assets/img/empty.png" alt="" class=" lazyload">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="post-list-small__body">
                                        <h3 class="post-list-small__entry-title">
                                            <a href="single-post.html">How Meditation Can Transform Your Business</a>
                                        </h3>
                                        <ul class="entry__meta">
                                            <li class="entry__meta-date">
                                                <i class="ui-date"></i>
                                                21 October, 2017
                                            </li>
                                        </ul>
                                    </div>
                                </article>
                            </li>
                            <li class="post-list-small__item">
                                <article class="post-list-small__entry clearfix">
                                    <div class="post-list-small__img-holder">
                                        <div class="thumb-container thumb-75">
                                            <a href="single-post.html">
                                                <img data-src="static/assets/img/blog/popular_post_3.jpg" src="static/assets/img/empty.png" alt="" class=" lazyload">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="post-list-small__body">
                                        <h3 class="post-list-small__entry-title">
                                            <a href="single-post.html">June in Africa: Taxi wars, smarter cities and increased investments</a>
                                        </h3>
                                        <ul class="entry__meta">
                                            <li class="entry__meta-date">
                                                <i class="ui-date"></i>
                                                21 October, 2017
                                            </li>
                                        </ul>
                                    </div>
                                </article>
                            </li>
                            <li class="post-list-small__item">
                                <article class="post-list-small__entry clearfix">
                                    <div class="post-list-small__img-holder">
                                        <div class="thumb-container thumb-75">
                                            <a href="single-post.html">
                                                <img data-src="static/assets/img/blog/popular_post_4.jpg" src="static/assets/img/empty.png" alt="" class=" lazyload">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="post-list-small__body">
                                        <h3 class="post-list-small__entry-title">
                                            <a href="single-post.html">PUBG Desert Map Finally Revealed, Here Are All The Details</a>
                                        </h3>
                                        <ul class="entry__meta">
                                            <li class="entry__meta-date">
                                                <i class="ui-date"></i>
                                                21 October, 2017
                                            </li>
                                        </ul>
                                    </div>
                                </article>
                            </li>
                        </ul>
                    </div>

                    <div class="tabs__content-pane" id="tab-latest">
                        <ul class="post-list-small">
                            <li class="post-list-small__item">
                                <article class="post-list-small__entry clearfix">
                                    <div class="post-list-small__img-holder">
                                        <div class="thumb-container thumb-75">
                                            <a href="single-post.html">
                                                <img data-src="static/assets/img/blog/popular_post_2.jpg" src="static/assets/img/empty.png" alt="" class=" lazyload">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="post-list-small__body">
                                        <h3 class="post-list-small__entry-title">
                                            <a href="single-post.html">How Meditation Can Transform Your Business</a>
                                        </h3>
                                        <ul class="entry__meta">
                                            <li class="entry__meta-date">
                                                <i class="ui-date"></i>
                                                21 October, 2017
                                            </li>
                                        </ul>
                                    </div>
                                </article>
                            </li>
                            <li class="post-list-small__item">
                                <article class="post-list-small__entry clearfix">
                                    <div class="post-list-small__img-holder">
                                        <div class="thumb-container thumb-75">
                                            <a href="single-post.html">
                                                <img data-src="static/assets/img/blog/popular_post_1.jpg" src="static/assets/img/empty.png" alt="" class=" lazyload">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="post-list-small__body">
                                        <h3 class="post-list-small__entry-title">
                                            <a href="single-post.html">Google is fixing its troubling burger emoji in Android 8.1</a>
                                        </h3>
                                        <ul class="entry__meta">
                                            <li class="entry__meta-date">
                                                <i class="ui-date"></i>
                                                21 October, 2017
                                            </li>
                                        </ul>
                                    </div>
                                </article>
                            </li>
                            <li class="post-list-small__item">
                                <article class="post-list-small__entry clearfix">
                                    <div class="post-list-small__img-holder">
                                        <div class="thumb-container thumb-75">
                                            <a href="single-post.html">
                                                <img data-src="static/assets/img/blog/popular_post_3.jpg" src="static/assets/img/empty.png" alt="" class=" lazyload">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="post-list-small__body">
                                        <h3 class="post-list-small__entry-title">
                                            <a href="single-post.html">June in Africa: Taxi wars, smarter cities and increased investments</a>
                                        </h3>
                                        <ul class="entry__meta">
                                            <li class="entry__meta-date">
                                                <i class="ui-date"></i>
                                                21 October, 2017
                                            </li>
                                        </ul>
                                    </div>
                                </article>
                            </li>
                            <li class="post-list-small__item">
                                <article class="post-list-small__entry clearfix">
                                    <div class="post-list-small__img-holder">
                                        <div class="thumb-container thumb-75">
                                            <a href="single-post.html">
                                                <img data-src="static/assets/img/blog/popular_post_4.jpg" src="static/assets/img/empty.png" alt="" class=" lazyload">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="post-list-small__body">
                                        <h3 class="post-list-small__entry-title">
                                            <a href="single-post.html">PUBG Desert Map Finally Revealed, Here Are All The Details</a>
                                        </h3>
                                        <ul class="entry__meta">
                                            <li class="entry__meta-date">
                                                <i class="ui-date"></i>
                                                21 October, 2017
                                            </li>
                                        </ul>
                                    </div>
                                </article>
                            </li>
                        </ul>
                    </div>

                    <div class="tabs__content-pane" id="tab-comments">
                        <ul class="post-list-small">
                            <li class="post-list-small__item">
                                <article class="post-list-small__entry clearfix">
                                    <div class="post-list-small__img-holder">
                                        <div class="thumb-container thumb-75">
                                            <a href="single-post.html">
                                                <img data-src="static/assets/img/blog/popular_post_3.jpg" src="static/assets/img/empty.png" alt="" class=" lazyload">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="post-list-small__body">
                                        <h3 class="post-list-small__entry-title">
                                            <a href="single-post.html">June in Africa: Taxi wars, smarter cities and increased investments</a>
                                        </h3>
                                        <ul class="entry__meta">
                                            <li class="entry__meta-date">
                                                <i class="ui-date"></i>
                                                21 October, 2017
                                            </li>
                                        </ul>
                                    </div>
                                </article>
                            </li>
                            <li class="post-list-small__item">
                                <article class="post-list-small__entry clearfix">
                                    <div class="post-list-small__img-holder">
                                        <div class="thumb-container thumb-75">
                                            <a href="single-post.html">
                                                <img data-src="static/assets/img/blog/popular_post_1.jpg" src="static/assets/img/empty.png" alt="" class=" lazyload">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="post-list-small__body">
                                        <h3 class="post-list-small__entry-title">
                                            <a href="single-post.html">Google is fixing its troubling burger emoji in Android 8.1</a>
                                        </h3>
                                        <ul class="entry__meta">
                                            <li class="entry__meta-date">
                                                <i class="ui-date"></i>
                                                21 October, 2017
                                            </li>
                                        </ul>
                                    </div>
                                </article>
                            </li>
                            <li class="post-list-small__item">
                                <article class="post-list-small__entry clearfix">
                                    <div class="post-list-small__img-holder">
                                        <div class="thumb-container thumb-75">
                                            <a href="single-post.html">
                                                <img data-src="static/assets/img/blog/popular_post_2.jpg" src="static/assets/img/empty.png" alt="" class=" lazyload">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="post-list-small__body">
                                        <h3 class="post-list-small__entry-title">
                                            <a href="single-post.html">How Meditation Can Transform Your Business</a>
                                        </h3>
                                        <ul class="entry__meta">
                                            <li class="entry__meta-date">
                                                <i class="ui-date"></i>
                                                21 October, 2017
                                            </li>
                                        </ul>
                                    </div>
                                </article>
                            </li>
                            <li class="post-list-small__item">
                                <article class="post-list-small__entry clearfix">
                                    <div class="post-list-small__img-holder">
                                        <div class="thumb-container thumb-75">
                                            <a href="single-post.html">
                                                <img data-src="static/assets/img/blog/popular_post_4.jpg" src="static/assets/img/empty.png" alt="" class=" lazyload">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="post-list-small__body">
                                        <h3 class="post-list-small__entry-title">
                                            <a href="single-post.html">PUBG Desert Map Finally Revealed, Here Are All The Details</a>
                                        </h3>
                                        <ul class="entry__meta">
                                            <li class="entry__meta-date">
                                                <i class="ui-date"></i>
                                                21 October, 2017
                                            </li>
                                        </ul>
                                    </div>
                                </article>
                            </li>
                        </ul>
                    </div>

                </div> <!-- end tab content -->
            </div> <!-- end tabs -->
        </div> <!-- end widget popular/latest posts -->

        <!-- 广告 -->
        <div class="widget widget_media_image">
            <a href="#">
                <img src="static/assets/img/blog/placeholder_300.jpg" alt="">
            </a>
        </div>

        <!-- 关注作者 -->
        <div class="widget widget-socials">
            <h4 class="widget-title">关注作者</h4>
            <div class="socials">
                <a class="social social-facebook social--large" href="#" title="facebook" target="_blank" aria-label="facebook">
                    <i class="ui-facebook"></i>
                </a><!--
              --><a class="social social-twitter social--large" href="#" title="twitter" target="_blank" aria-label="twitter">
                    <i class="ui-twitter"></i>
                </a><!--
              --><a class="social social-google-plus social--large" href="#" title="google" target="_blank" aria-label="google">
                    <i class="ui-google"></i>
                </a><!--
              --><a class="social social-instagram social--large" href="#" title="instagram" target="_blank" aria-label="instagram">
                    <i class="ui-instagram"></i>
                </a><!--
              --><a class="social social-youtube social--large" href="#" title="youtube" target="_blank" aria-label="youtube">
                    <i class="ui-youtube"></i>
                </a><!--
              --><a class="social social-rss social--large" href="#" title="rss" target="_blank" aria-label="rss">
                    <i class="ui-rss"></i>
                </a>
            </div>
        </div>

        <!-- 热门话题 -->
        <div class="widget widget-gallery-sm">
            <h4 class="widget-title text-left">热门话题</h4>
            <ul class="widget-gallery-sm__list">
                <li class="widget-gallery-sm__item">
                    <a href="#"><img src="static/assets/img/blog/placeholder_125.jpg" alt=""></a>
                </li>
                <li class="widget-gallery-sm__item">
                    <a href="#"><img src="static/assets/img/blog/placeholder_125.jpg" alt=""></a>
                </li>
                <li class="widget-gallery-sm__item">
                    <a href="#"><img src="static/assets/img/blog/placeholder_125.jpg" alt=""></a>
                </li>
                <li class="widget-gallery-sm__item">
                    <a href="#"><img src="static/assets/img/blog/placeholder_125.jpg" alt=""></a>
                </li>
            </ul>
        </div>

        <!-- 轮播图 -->
        <div class="widget">
            <div id="owl-single" class="owl-carousel owl-theme">

                <article class="entry">
                    <div class="entry__img-holder mb-0">
                        <a href="single-post.html">
                            <div class="thumb-bg-holder">
                                <img data-src="static/assets/img/blog/featured_post_img_1.jpg" src="static/assets/img/blog/featured_post_img_1.jpg" class="entry__img owl-lazy" alt="">
                                <div class="bottom-gradient"></div>
                            </div>
                        </a>
                    </div>

                    <div class="thumb-text-holder">
                        <h2 class="thumb-entry-title thumb-entry-title--sm">
                            <a href="single-post.html">Myanmar little monk reading book outside monastery</a>
                        </h2>
                    </div>
                </article>

                <article class="entry">
                    <div class="entry__img-holder mb-0">
                        <a href="single-post.html">
                            <div class="thumb-bg-holder">
                                <img data-src="static/assets/img/blog/featured_post_img_2.jpg" src="static/assets/img/blog/featured_post_img_2.jpg" class="entry__img owl-lazy" alt="">
                                <div class="bottom-gradient"></div>
                            </div>
                        </a>
                    </div>

                    <div class="thumb-text-holder">
                        <h2 class="thumb-entry-title thumb-entry-title--sm">
                            <a href="single-post.html">Spectacular display of northern lights illuminates sky</a>
                        </h2>
                    </div>
                </article>


            </div>
        </div>

        <!-- 最新评论 -->
        <div class="widget widget-reviews">
            <h4 class="widget-title">最新评论</h4>
            <ul class="post-list-small">
                <li class="post-list-small__item">
                    <article class="post-list-small__entry clearfix">
                        <div class="post-list-small__img-holder">
                            <div class="thumb-container thumb-75">
                                <a href="single-post.html">
                                    <img data-src="static/assets/img/blog/review_post_1.jpg" src="static/assets/img/blog/review_post_1.jpg" alt="" class=" lazyload">
                                </a>
                            </div>
                        </div>
                        <div class="post-list-small__body">
                            <h3 class="post-list-small__entry-title">
                                <a href="single-post.html">My First Impressions of iPhone X</a>
                            </h3>
                            <ul class="entry__meta">
                                <li class="entry__meta-rating">
                                    <i class="ui-star"></i><!--
                        --><i class="ui-star"></i><!--
                        --><i class="ui-star"></i><!--
                        --><i class="ui-star"></i><!--
                        --><i class="ui-star-outline"></i>
                                </li>
                            </ul>
                        </div>
                    </article>
                </li>
                <li class="post-list-small__item">
                    <article class="post-list-small__entry clearfix">
                        <div class="post-list-small__img-holder">
                            <div class="thumb-container thumb-75">
                                <a href="single-post.html">
                                    <img data-src="static/assets/img/blog/review_post_2.jpg" src="static/assets/img/empty.png" alt="" class=" lazyload">
                                </a>
                            </div>
                        </div>
                        <div class="post-list-small__body">
                            <h3 class="post-list-small__entry-title">
                                <a href="single-post.html">The Best Laptops for Kids</a>
                            </h3>
                            <ul class="entry__meta">
                                <li class="entry__meta-rating">
                                    <i class="ui-star"></i><!--
                        --><i class="ui-star"></i><!--
                        --><i class="ui-star"></i><!--
                        --><i class="ui-star"></i><!--
                        --><i class="ui-star-outline"></i>
                                </li>
                            </ul>
                        </div>
                    </article>
                </li>
                <li class="post-list-small__item">
                    <article class="post-list-small__entry clearfix">
                        <div class="post-list-small__img-holder">
                            <div class="thumb-container thumb-75">
                                <a href="single-post.html">
                                    <img data-src="static/assets/img/blog/review_post_3.jpg" src="static/assets/img/empty.png" alt="" class=" lazyload">
                                </a>
                            </div>
                        </div>
                        <div class="post-list-small__body">
                            <h3 class="post-list-small__entry-title">
                                <a href="single-post.html">PS4 Joypads Pre-Orders Start Friday in NYC</a>
                            </h3>
                            <ul class="entry__meta">
                                <li class="entry__meta-rating">
                                    <i class="ui-star"></i><!--
                        --><i class="ui-star"></i><!--
                        --><i class="ui-star"></i><!--
                        --><i class="ui-star"></i><!--
                        --><i class="ui-star-outline"></i>
                                </li>
                            </ul>
                        </div>
                    </article>
                </li>
                <li class="post-list-small__item">
                    <article class="post-list-small__entry clearfix">
                        <div class="post-list-small__img-holder">
                            <div class="thumb-container thumb-75">
                                <a href="single-post.html">
                                    <img data-src="static/assets/img/blog/review_post_4.jpg" src="static/assets/img/empty.png" alt="" class=" lazyload">
                                </a>
                            </div>
                        </div>
                        <div class="post-list-small__body">
                            <h3 class="post-list-small__entry-title">
                                <a href="single-post.html">Hands on: Parrot AR Drone 2.0 review</a>
                            </h3>
                            <ul class="entry__meta">
                                <li class="entry__meta-rating">
                                    <i class="ui-star"></i><!--
                        --><i class="ui-star"></i><!--
                        --><i class="ui-star"></i><!--
                        --><i class="ui-star"></i><!--
                        --><i class="ui-star-outline"></i>
                                </li>
                            </ul>
                        </div>
                    </article>
                </li>
            </ul>
        </div>

        <!-- 标签 -->
        <div class="widget widget_tag_cloud">
            <h4 class="widget-title">标签</h4>
            <div class="tagcloud">
                <a href="#">Magazine</a>
                <a href="#">Creative</a>
                <a href="#">Responsive</a>
                <a href="#">Modern</a>
                <a href="#">Tech</a>
                <a href="#">WordPress</a>
                <a href="#">Website</a>
                <a href="#">News</a>
            </div>
        </div>

    </aside> <!-- end sidebar -->
</div>
<!-- end content -->


