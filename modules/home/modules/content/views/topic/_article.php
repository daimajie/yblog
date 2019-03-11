<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/2/19
 * Time : 19:03
 */

//$key：混合类型，键的值与数据项相关联。
//$index：整型，是由数据提供者返回的数组中以0起始的数据项的索引。
//$widget：类型是ListView，是小部件的实例。

use app\components\ViewHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\home\widgets\AdvertBar;
?>
<article class="entry post-list">
        <div class="entry__img-holder post-list__img-holder">
            <a href="<?= Url::to(['/home/content/article/view','id'=>$model->id])?>">
                <div class="thumb-container thumb-75">
                    <img data-src="<?= ViewHelper::showImage($model->image)?>" src="<?= ViewHelper::staticPath('img/empty.png')?>" class="entry__img lazyload" alt="">
                </div>
            </a>
        </div>

        <div class="entry__body post-list__body">
            <div class="entry__header">
                <a href="<?= Url::to(['/home/content/topic/view','id'=>$model->topic->id])?>" class="entry__meta-category"><?= Html::encode($model->topic->name)?></a>
                <h2 class="entry__title">
                    <a href="<?= Url::to(['/home/content/article/view','id'=>$model->id])?>"><?= Html::encode($model->title)?></a>
                </h2>
                <ul class="entry__meta">
                    <li class="entry__meta-author">
                        <i class="ui-author"></i>
                        <a href="<?= Url::to(['/home/member/author/index','id'=>$model->user->id])?>"><?= ViewHelper::username($model->user->username, $model->user->nickname)?></a>
                    </li>
                    <li class="entry__meta-date">
                        <i class="ui-date"></i>
                        <?= ViewHelper::time($model->created_at)?>
                    </li>
                    <li class="entry__meta-comments">
                        <i class="ui-comments"></i>
                        <?= $model->comment?>
                    </li>
                </ul>
            </div>
            <div class="entry__excerpt">
                <p><?= Html::encode($model->brief)?></p>
            </div>
        </div>
    </article>

<?php
//文章列表显示一半的时候加个广告
if($index+1 == floor($count/2)){
    echo AdvertBar::widget();
}
?>
