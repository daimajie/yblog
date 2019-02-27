<?php
use app\components\ViewHelper;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\helpers\Url;
use app\models\content\Topic;


/*$model 用户模型*/
/*$dataProvide 文章数据提供者*/

//搜索的属性
$title = trim(Yii::$app->request->get('title', ''));
?>
<!-- Content -->
<div class="row">

    <!-- post content -->
    <div class="col-lg-8 blog__content mb-30">

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
        }catch (\Exception $e){

        }

        ?>
    </div>
    <!-- end col -->

    <!-- Sidebar -->
    <aside class="col-lg-4 sidebar sidebar--right">
        <!--search article-->
        <div class="widget widget_mc4wp_form_widget text-center">
            <?= Html::beginForm(['index','user_id'=>$model['id']], 'get',['class'=>'search-form']) ?>
            <?= Html::input('text', 'title', !empty($title)?trim($title):'', [
                'placeholder'=>'文章标题',
                'autocomplete'=>"off",
                'class' => 'search-input mb-0'
            ]) .
            Html::submitButton('<i class="ui-search search-icon"></i>', [
                'class' => 'search-button btn btn-lg btn-color btn-button'
            ])
            ?>
            <?= Html::endForm() ?>
        </div>

        <!-- user info -->
        <div class="widget widget_mc4wp_form_widget text-center">
            <div class="sidebar-about">
                <div class="about-img entry-author text-center">
                    <img src="<?= ViewHelper::staticPath('img/blog/list_post_img_1.jpg')?>" class="img-thumbnail">
                </div>
                <h5><i class="ui-author"></i> <?= ViewHelper::username($model->username, $model->nickname)?></h5>
                <p><small> 文章 - <?= $model->author?>  /  话题 - <?= $topicCount?> </small></p>
                <p class="mb-3"><?= empty($model['user']['intro']) ? '博主好懒～什么也没留下！' : Html::encode($model['user']['intro'])?></p>
            </div>
        </div>

        <!--作者话题-->
        <div class="widget widget-reviews">
            <h4 class="widget-title">作者话题</h4>
            <ul class="post-list-small">
                <?php
                foreach($category as $key => $val):
                ?>
                <li class="post-list-small__item">
                    <article class="post-list-small__entry clearfix">
                        <div class="post-list-small__img-holder">
                            <div class="thumb-container thumb-75">
                                <a href="<?= Url::to(['/home/content/topic/view','id'=>$val['id']])?>">
                                    <img data-src="<?= ViewHelper::showImage($val['image'])?>" src="<?= ViewHelper::showImage($val['image'])?>" alt="" class=" lazyloaded">
                                </a>
                            </div>
                        </div>
                        <div class="post-list-small__body">
                            <h3 class="post-list-small__entry-title">
                                <a href="<?= Url::to(['/home/content/topic/view','id'=>$val['id']])?>"><?= Html::encode($val['name'])?></a>
                            </h3>
                            <ul class="entry__meta">
                                <li class="entry__meta-date">
                                    <i class="ui-author"></i>
                                    <?= ViewHelper::username($model->username, $model->nickname)?>
                                </li>
                                <li class="entry__meta-date">
                                    <i class="ui-xing"></i>
                                    <?php
                                    $tmp = [
                                        Topic::STATUS_NORMAL => '连载中',
                                        Topic::STATUS_FINISH => '完结',
                                        Topic::STATUS_RECYCLE => '回收站',
                                    ];
                                    echo $tmp[$val['status']];
                                    ?>
                                </li>
                                <li class="entry__meta-comments">
                                    <i class="ui-flickr"></i>
                                    <?= $val['count']?>篇
                                </li>
                            </ul>
                            <ul class="entry__meta">
                                <li class="entry__meta-date">
                                    <i class="ui-date"></i>
                                    <?= ViewHelper::time($val['updated_at'])?>
                                </li>
                            </ul>
                        </div>
                    </article>
                </li>
                <?php
                endforeach;
                ?>
                <li class="post-list-small__item">
                    <article class="post-list-small__entry clearfix">
                        <div class="post-list-small__body">
                            <h3 class="post-list-small__entry-title">
                                <a href="<?= Url::to(['/home/content/topic/index', 'user_id'=>$model->id])?>">查看作者更多话题</a>
                            </h3>
                        </div>
                    </article>
                </li>

            </ul>
        </div>

    </aside>
    <!-- end sidebar -->

</div>
<!-- end content -->