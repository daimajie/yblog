<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/2/19
 * Time : 17:18
 */
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\helpers\Url;
use app\components\ViewHelper;
use app\models\content\Topic;


$this->title = Html::encode($model->name);

//搜索的属性
$tag_id = Yii::$app->request->get('tag_id', '');
$title = trim(Yii::$app->request->get('title', ''));
?>

<!-- Content -->
<div class="row">

    <!-- post content -->
    <div class="col-lg-8 blog__content mb-30">
        <!--话题介绍-->
        <div class="title-wrap">
            <div class="jumbotron jumbotron-fluid">
                <div class="container mb-3">
                    <h3 class="display-4"><?= Html::encode($model['name'])?></h3>
                    <p class="lead"><?= Html::encode($model['desc'])?></p>
                    <footer class="blockquote-footer"><ul class="entry__meta">
                            <li class="entry__meta-author">
                                <i class="ui-author"></i>
                                <a href="<?= Url::to(['/home/member/author/index', 'id'=>$model['user']['id']])?>"><?= ViewHelper::username($model['user']['username'],$model['user']['nickname'])?></a>
                            </li>
                            <li class="entry__meta-date">
                                <i class="ui-xing"></i>
                                <?= $model['status'] == Topic::STATUS_NORMAL ? '连载中...' : '完结'?>
                            </li>
                            <li class="entry__meta-comments">
                                <i class="ui-flickr"></i>
                                <?= $model['count']?>篇
                            </li>
                            <li class="entry__meta-date">
                                <i class="ui-date"></i>
                                <?= ViewHelper::time($model['updated_at'])?>
                            </li>
                        </ul>
                    </footer>
                    <!--<a href="#" class="btn btn-sm btn-light">
                        <span>点击关注</span>
                    </a>-->
                </div>
            </div>
        </div>

        <!--文章列表-->
        <?php
        //话题列表
        echo ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '_article',
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
        ?>


    </div> <!-- end col -->

    <!-- Sidebar -->
    <aside class="col-lg-4 sidebar sidebar--right">
        <!--search article-->
        <div class="widget widget_mc4wp_form_widget text-center">
            <?= Html::beginForm(['view','id'=>$model['id']], 'get',['class'=>'search-form']) ?>
            <?= Html::input('text', 'title', !empty($title)?trim($title):'', [
                'placeholder'=>'话题名称',
                'autocomplete'=>"off",
                'class' => 'search-input mb-0'
            ]) .
            Html::submitButton('<i class="ui-search search-icon"></i>', [
                'class' => 'search-button btn btn-lg btn-color btn-button'
            ])
            ?>
            <?= Html::endForm() ?>
        </div>
        <!-- Widget Tags -->
        <div class="widget widget_tag_cloud">
            <h4 class="widget-title">云标签</h4>
            <div class="tagcloud">
                <?php if(!empty($cloudTags)):?>
                <a class="<?= !is_numeric($tag_id)?'active':''?>" href="<?= Url::to(['', 'id'=>$model['id'], 'tag_id'=>null])?>">全部</a>
                <?php foreach ($cloudTags as $cloudTag):?>
                    <a class="<?= ($tag_id==$cloudTag['id'])?'active':''?>" href="<?= Url::to(['','id'=>$model['id'],  'tag_id'=>$cloudTag['id']])?>"><?= $cloudTag['name']?></a>
                <?php endforeach;?>
                <a class="<?= empty($tag_id)?'active':''?>" href="<?= Url::to(['','id'=>$model['id'],  'tag_id'=>'0'])?>">无标签</a>
                <?php endif;?>
            </div>
        </div>



    </aside>
    <!-- end sidebar -->
</div>
<!-- end content -->
