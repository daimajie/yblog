<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use yii\widgets\ListView;



//搜索的属性
$title = trim(Yii::$app->request->get('title', ''));

$this->title = '搜索结果 - ' . isset($title)?trim($title):'';
?>
<!-- Content -->
<div class="row">

    <!-- post content -->
    <div class="col-lg-8 blog__content mb-30">

        <h3 class="mb-20">搜索结果</h3>
        <?= Html::beginForm(['search'], 'get',[
            'class'=>"search-form mb-20",
        ]) ?>
        <?= Html::input('text', 'title', isset($title)?trim($title):'', [
            'placeholder'=>'文章标题',
            'autocomplete'=>"off"
        ]) .
        Html::submitButton('<i class="ui-search search-icon"></i>', [
            'class' => 'search-button btn btn-lg btn-color btn-button'
        ])
        ?>
        <?= Html::endForm() ?>
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

    </div> <!-- end col -->

    <!-- Sidebar -->
    <aside class="col-lg-4 sidebar sidebar--right">

        <!--qrcode-->
        <div class="widget widget_mc4wp_form_widget text-center">
            <img width="100%" src="static/assets/img/blog/rqcode.png" alt="关注微信公众号" class="mb-3 ">
            <h4 class="widget-title">扫码直接下载APP</h4>
        </div>

    </aside> <!-- end sidebar -->

</div>
<!-- end content -->
