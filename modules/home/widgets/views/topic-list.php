<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/3/10
 * Time : 20:19
 */
use yii\helpers\Url;
use app\components\ViewHelper;
use yii\helpers\Html;
use app\models\content\Topic;

?>
<div class="widget widget-reviews">
    <h4 class="widget-title"><?= $title?></h4>
    <ul class="post-list-small">
        <?php
        foreach ($topics as $topic):
        ?>
        <li class="post-list-small__item">
            <article class="post-list-small__entry clearfix">
                <div class="post-list-small__img-holder">
                    <div class="thumb-container thumb-75">
                        <a href="<?= Url::to([$show ? '/home/write/topic/show' : '/home/content/topic/view', 'id'=>$topic['id']])?>">
                            <img data-src="<?= ViewHelper::showImage($topic['image'])?>" src="<?= ViewHelper::showImage($topic['image'])?>" class=" lazyloaded">
                        </a>
                    </div>
                </div>
                <div class="post-list-small__body">
                    <h3 class="post-list-small__entry-title">
                        <a href="<?= Url::to([$show ? '/home/write/topic/show' : '/home/content/topic/view', 'id'=>$topic['id']])?>">
                            <?= Html::encode($topic['name'])?>
                        </a>
                    </h3>
                    <ul class="entry__meta">
                        <li class="entry__meta-date">
                            <i class="ui-author"></i>
                            <?= ViewHelper::username($topic['user']['username'], $topic['user']['nickname'])?>
                        </li>
                        <li class="entry__meta-date">
                            <i class="ui-xing"></i>
                            <?php
                            $tmp = [
                                Topic::STATUS_NORMAL => '连载中',
                                Topic::STATUS_FINISH => '完结',
                                Topic::STATUS_RECYCLE => '回收站',
                            ];
                            echo $tmp[$topic['status']];
                            ?>
                        </li>
                        <li class="entry__meta-comments">
                            <i class="ui-flickr"></i>
                            <?= $topic['count']?>篇
                        </li>
                    </ul>
                    <ul class="entry__meta">
                        <li class="entry__meta-date">
                            <i class="ui-date"></i>
                            <?= ViewHelper::time($topic['updated_at'])?>
                        </li>
                    </ul>
                </div>
            </article>
        </li>
        <?php
        endforeach;
        if(empty($topics)) echo '<li>暂无数据。</li>';
        if($more):
        ?>
        <li class="post-list-small__item">
            <article class="post-list-small__entry clearfix">
                <div class="post-list-small__body">
                    <h3 class="post-list-small__entry-title">
                        <a href="<?= Url::to(['/home/content/topic/index', 'user_id'=>$user_id])?>">查看作者更多话题</a>
                    </h3>
                </div>
            </article>
        </li>
        <?php
        endif;
        ?>
    </ul>
</div>
