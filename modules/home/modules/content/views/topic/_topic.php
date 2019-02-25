<?php
use yii\helpers\Html;
use app\components\ViewHelper;
use yii\helpers\Url;
?>
<div class="col-md-3">
    <article class="entry">
        <div class="entry__img-holder">
            <a href="<?= Url::to(['/home/content/topic/view','id'=>$model->id])?>">
                <div class="thumb-container thumb-75">
                    <img data-src="<?= ViewHelper::showImage($model->image)?>" src="<?= ViewHelper::staticPath('img/empty.png')?>" class="entry__img lazyload" alt="">
                </div>
            </a>
        </div>

        <div class="entry__body">
            <div class="entry__header">
                <h2 class="entry__title">
                    <a href="<?= Url::to(['/home/content/topic/view','id'=>$model->id])?>"><?= Html::encode($model->name) ?></a>
                </h2>
                <ul class="entry__meta">
                    <li class="entry__meta-author">
                        <i class="ui-author"></i>
                        <a href="<?= Url::to(['/home/member/author/index','id'=>$model->user->id])?>"><?= Html::encode(ViewHelper::username($model->user->username, $model->user->nickname)) ?></a>
                    </li>
                    <li class="entry__meta-date">
                        <i class="ui-date"></i>
                        <?= ViewHelper::time($model->created_at) ?>
                    </li>
                    <li class="entry__meta-comments">
                        <i class="ui-flickr"></i>
                        <?= $model->count ?>
                    </li>
                </ul>
            </div>
            <div class="entry__excerpt">
                <p><?= Html::encode($model->desc) ?></p>
            </div>
        </div>
    </article>
</div>