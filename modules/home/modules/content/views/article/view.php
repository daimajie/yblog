<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/2/20
 * Time : 13:45
 */
use yii\widgets\Breadcrumbs;
use app\components\ViewHelper;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
?>
<!-- Content -->
<div class="row">

    <!-- post content -->
    <div class="col-lg-8 blog__content mb-30">

        <!-- Breadcrumbs -->
        <?=
        Breadcrumbs::widget([
            'options' => [
                'class' => 'breadcrumbs',
            ],
            //'encodeLabels' => false,
            'homeLink' => [
                'encode' => false,
                'class'=>'breadcrumbs__url',
                'label' => '<i class="ui-home"></i>',
                'url' => ['/index']
            ],

            'activeItemTemplate' => "<li class='breadcrumbs__item breadcrumbs__item--current'>{link}</li>",
            'itemTemplate' => "<li class='breadcrumbs__item'>{link}</li>",
            'links' => [

                ['label' => $model['topic']['name'], 'url' => ['/home/content/topic/view', 'id' => $model['topic']['id']],'class'=>'breadcrumbs__url'],
                ViewHelper::truncate_utf8_string(Html::encode($model['title']),6)
            ],
        ]) ?>

        <!-- standard post -->
        <article class="entry">

            <div class="single-post__entry-header entry__header">
                <a href="#" class="entry__meta-category"><?= Html::encode($model['topic']['name'])?></a>
                <h3>
                    <?= Html::encode($model['title'])?>
                </h3>

                <ul class="entry__meta">
                    <li class="entry__meta-author">
                        <i class="ui-author"></i>
                        <a href="#"><?= ViewHelper::username($model['user']['username'], $model['user']['nickname'])?></a>
                    </li>
                    <li class="entry__meta-date">
                        <i class="ui-date"></i>
                        <?= ViewHelper::time($model['created_at'])?>
                    </li>
                    <li class="entry__meta-comments">
                        <i class="ui-comments"></i>
                        <a href="#"><?= $model['comment']?></a>
                    </li>
                </ul>
            </div>


            <div class="entry__article">
                <!--article content-->
                <div class="content">
                    <?= HtmlPurifier::process($model['content']['content'])?>
                </div>
                <!--end article content-->

                <!-- tags -->
                <div class="entry__tags">
                    <span class="entry__tags-label">标签:</span>
                    <?php
                    foreach ($model['tags'] as $tag):
                    ?>
                    <a href="<?= Url::to(['topic/view','id'=>$model['topic_id'],'tag_id'=>$tag['id']])?>" rel="tag"><?= Html::encode($tag['name'])?></a>
                    <?php endforeach;?>
                </div>
                <!-- end tags -->

            </div>
            <!-- end entry article -->


            <!-- Author -->
            <div class="title-wrap mt-40">
                <h6 class="uppercase">文章作者 - </h6>
            </div>
            <div class="entry-author clearfix">
                <img width="100" height="100" data-src="<?= ViewHelper::avatar($model['user']['image'])?>" src="<?= ViewHelper::staticPath('img/empty.png')?>" class="avatar lazyload">
                <div class="entry-author__info">
                    <h6 class="entry-author__name">
                        <a href="#"><?= ViewHelper::username($model['user']['username'], $model['user']['nickname'])?></a>
                    </h6>
                    <p style="font-size: 14px;" class="mb-0"><?= empty($model['user']['intro']) ? '这家伙很懒, 什么也没留下。' : Html::encode($model['user']['intro'])?></p>
                </div>
            </div>

            <!-- Prev / Next Post -->
            <nav class="entry-navigation">
                <div class="clearfix">
                    <div class="entry-navigation--left">
                        <i class="ui-arrow-left"></i>
                        <span class="entry-navigation__label">上一页</span>
                        <div class="entry-navigation__link">
                            <a href="<?= empty($prevAndNext['prev']) ? 'javascript:void(0)' : Url::to(['', 'id'=>$prevAndNext['prev']['id']])?>" rel="next">
                                <?= empty($prevAndNext['prev']) ? '已经是第一篇文章了' : Html::encode($prevAndNext['prev']['title'])?>
                            </a>
                        </div>
                    </div>
                    <div class="entry-navigation--right">
                        <span class="entry-navigation__label">下一页</span>
                        <i class="ui-arrow-right"></i>
                        <div class="entry-navigation__link">
                            <a href="<?= empty($prevAndNext['next']) ? 'javascript:void(0)' : Url::to(['', 'id'=>$prevAndNext['next']['id']])?>" rel="prev">
                                <?= empty($prevAndNext['next']) ? '已经是最后一篇文章了' : Html::encode($prevAndNext['next']['title'])?>
                            </a>
                        </div>
                    </div>
                </div>
            </nav>

        </article> <!-- end standard post -->


        <!-- Comments -->
        <div class="entry-comments mt-30">
            <div class="title-wrap mt-40">
                <h6 class="uppercase">评论数 - 3</h6>
            </div>
            <ul class="comment-list">
                <li class="comment">
                    <div class="comment-body">
                        <div class="comment-avatar">
                            <img alt="" src="img/blog/comment_1.jpg">
                        </div>
                        <div class="comment-text">
                            <h6 class="comment-author">Joeby Ragpa</h6>
                            <div class="comment-metadata">
                                <a href="#" class="comment-date">July 17, 2017 at 12:48 pm</a>
                            </div>
                            <p>This template is so awesome. I didn’t expect so many features inside. E-commerce pages are very useful, you can launch your online store in few seconds. I will rate 5 stars.</p>
                            <a href="#" class="comment-reply">Reply</a>
                        </div>
                    </div>

                    <ul class="children">
                        <li class="comment">
                            <div class="comment-body">
                                <div class="comment-avatar">
                                    <img alt="" src="img/blog/comment_2.jpg">
                                </div>
                                <div class="comment-text">
                                    <h6 class="comment-author">Alexander Samokhin</h6>
                                    <div class="comment-metadata">
                                        <a href="#" class="comment-date">July 17, 2017 at 12:48 pm</a>
                                    </div>
                                    <p>This template is so awesome. I didn’t expect so many features inside. E-commerce pages are very useful, you can launch your online store in few seconds. I will rate 5 stars.</p>
                                    <a href="#" class="comment-reply">Reply</a>
                                </div>
                            </div>
                        </li> <!-- end reply comment -->
                    </ul>

                </li> <!-- end 1-2 comment -->

                <li>
                    <div class="comment-body">
                        <div class="comment-avatar">
                            <img alt="" src="img/blog/comment_3.jpg">
                        </div>
                        <div class="comment-text">
                            <h6 class="comment-author">Chris Root</h6>
                            <div class="comment-metadata">
                                <a href="#" class="comment-date">July 17, 2017 at 12:48 pm</a>
                            </div>
                            <p>This template is so awesome. I didn’t expect so many features inside. E-commerce pages are very useful, you can launch your online store in few seconds. I will rate 5 stars.</p>
                            <a href="#" class="comment-reply">Reply</a>
                        </div>
                    </div>
                </li> <!-- end 3 comment -->

            </ul>
        </div> <!-- end comments -->


        <!-- Comment Form -->
        <div id="respond" class="comment-respond">
            <div class="title-wrap">
                <h5 class="comment-respond__title uppercase">Leave a Reply</h5>
            </div>
            <form id="form" class="comment-form" method="post" action="#">
                <p class="comment-form-comment">
                    <!-- <label for="comment">Comment</label> -->
                    <textarea id="comment" name="comment" rows="5" required="required" placeholder="Comment*"></textarea>
                </p>

                <div class="row row-20">
                    <div class="col-lg-4">
                        <input name="name" id="name" type="text" placeholder="Name*">
                    </div>
                    <div class="col-lg-4">
                        <input name="email" id="email" type="email" placeholder="Email*">
                    </div>
                    <div class="col-lg-4">
                        <input name="website" id="website" type="text" placeholder="Website">
                    </div>
                </div>

                <p class="comment-form-submit">
                    <input type="submit" class="btn btn-lg btn-color btn-button" value="Post Comment" id="submit-message">
                </p>

            </form>
        </div> <!-- end comment form -->

    </div> <!-- end col -->

    <!-- Sidebar -->
    <aside class="col-lg-4 sidebar sidebar--right">
    </aside>
    <!-- end sidebar -->
</div>
<!-- end content -->
