<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/2/19
 * Time : 19:03
 */
use app\components\ViewHelper;
use yii\helpers\Html;
?>
<article class="entry post-list">
    <div class="entry__img-holder post-list__img-holder">
        <a href="single-post.html">
            <div class="thumb-container thumb-75">
                <img data-src="<?= ViewHelper::showImage($model->image)?>" src="<?= ViewHelper::staticPath('img/empty.png')?>" class="entry__img lazyload" alt="">
            </div>
        </a>
    </div>

    <div class="entry__body post-list__body">
        <div class="entry__header">
            <a href="#" class="entry__meta-category"><?= Html::encode($model->topic->name)?></a>
            <h2 class="entry__title">
                <a href="single-post.html"><?= Html::encode($model->title)?></a>
            </h2>
            <ul class="entry__meta">
                <li class="entry__meta-author">
                    <i class="ui-author"></i>
                    <a href="#"><?= ViewHelper::username($model->user->username, $model->user->nickname)?></a>
                </li>
                <li class="entry__meta-date">
                    <i class="ui-date"></i>
                    <?= ViewHelper::time($model->created_at)?>
                </li>
                <li class="entry__meta-comments">
                    <i class="ui-comments"></i>
                    <a href="#"><?= $model->comment?></a>
                </li>
            </ul>
        </div>
        <div class="entry__excerpt">
            <p><?= Html::encode($model->brief)?></p>
        </div>
    </div>
</article>