<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/3/6
 * Time : 15:09
 */
use yii\helpers\Url;
use yii\helpers\Html;
use app\components\ViewHelper;

?>
<!-- 热门文章 -->
<div class="widget widget-tabpost">
    <div class="tabs widget-tabpost__tabs">
        <ul class="tabs__list widget-tabpost__tabs-list">
            <li class="tabs__item widget-tabpost__tabs-item tabs__item--active">
                <a href="#tab-trending" class="tabs__url tabs__trigger widget-tabpost__tabs-url">阅读排行</a>
            </li>
            <li class="tabs__item widget-tabpost__tabs-item">
                <a href="#tab-latest" class="tabs__url tabs__trigger widget-tabpost__tabs-url">评论最多</a>
            </li>
            <li class="tabs__item widget-tabpost__tabs-item">
                <a href="#tab-comments" class="tabs__url tabs__trigger widget-tabpost__tabs-url">最新评论</a>
            </li>
        </ul> <!-- end tabs -->

        <!-- tab content -->
        <div class="tabs__content tabs__content-trigger widget-tabpost__tabs-content">

            <div class="tabs__content-pane tabs__content-pane--active" id="tab-trending">
                <ul class="post-list-small">
                    <?php
                    foreach ($byComment as $key => $art):
                    ?>
                    <li class="post-list-small__item">
                        <article class="post-list-small__entry clearfix">
                            <div class="post-list-small__img-holder">
                                <div class="thumb-container thumb-75">
                                    <a href="<?= Url::to(['/home/content/article/view', 'id'=>$art['id']])?>">
                                        <img data-src="<?= ViewHelper::showImage($art['image'])?>" src="<?= ViewHelper::staticPath('img/empty.png')?>" alt="" class="lazyload">
                                    </a>
                                </div>
                            </div>
                            <div class="post-list-small__body">
                                <h3 class="post-list-small__entry-title">
                                    <a href="<?= Url::to(['/home/content/article/view', 'id'=>$art['id']])?>"><?= Html::encode($art['title'])?></a>
                                </h3>
                                <ul class="entry__meta">
                                    <li class="entry__meta-date">
                                        <i class="ui-date"></i>
                                        <?= ViewHelper::time($art['created_at'])?>
                                    </li>
                                </ul>
                            </div>
                        </article>
                    </li>
                    <?php
                    endforeach;
                    if(empty($byComment)) echo '<li>暂无数据。</li>';
                    ?>
                </ul>
            </div>

            <div class="tabs__content-pane" id="tab-latest">
                <ul class="post-list-small">
                    <?php
                    foreach ($byVisited as $key => $art):
                        ?>
                        <li class="post-list-small__item">
                            <article class="post-list-small__entry clearfix">
                                <div class="post-list-small__img-holder">
                                    <div class="thumb-container thumb-75">
                                        <a href="<?= Url::to(['/home/content/article/view', 'id'=>$art['id']])?>">
                                            <img data-src="<?= ViewHelper::showImage($art['image'])?>" src="<?= ViewHelper::staticPath('img/empty.png')?>" alt="" class="lazyload">
                                        </a>
                                    </div>
                                </div>
                                <div class="post-list-small__body">
                                    <h3 class="post-list-small__entry-title">
                                        <a href="<?= Url::to(['/home/content/article/view', 'id'=>$art['id']])?>"><?= Html::encode($art['title'])?></a>
                                    </h3>
                                    <ul class="entry__meta">
                                        <li class="entry__meta-date">
                                            <i class="ui-date"></i>
                                            <?= ViewHelper::time($art['created_at'])?>
                                        </li>
                                    </ul>
                                </div>
                            </article>
                        </li>
                    <?php
                    endforeach;
                    if(empty($byVisited)) echo '<li>暂无数据。</li>';
                    ?>
                </ul>
            </div>

            <div class="tabs__content-pane" id="tab-comments">
                <ul class="post-list-small">
                    <?php
                    foreach($comment as $item):
                    ?>
                    <li class="post-list-small__item">
                        <article class="post-list-small__entry clearfix">
                            <div class="post-list-small__body">
                                <h3 class="post-list-small__entry-title">
                                    <a href="<?= Url::to(['/home/content/article/view', 'id'=>$item['article_id']])?>">
                                        <?= ViewHelper::truncate_utf8_string(Html::encode($item['content']), '52')?>
                                    </a>
                                </h3>
                                <ul class="entry__meta">
                                    <li class="entry__meta-author">
                                        <i class="ui-author"></i>
                                        <a href="#">小王叶</a>
                                    </li>
                                    <li class="entry__meta-date">
                                        <i class="ui-date"></i>
                                        <?= ViewHelper::time($item['created_at'])?>
                                    </li>
                                </ul>
                            </div>
                        </article>
                    </li>
                    <?php
                    endforeach;
                    if(empty($comment)) echo '<li>暂无数据。</li>';
                    ?>

                </ul>
            </div>

        </div> <!-- end tab content -->
    </div> <!-- end tabs -->
</div>
<!-- end widget popular/latest posts -->
