<?php
use yii\widgets\ListView;
use app\modules\home\widgets\More;
use app\modules\home\widgets\HotTopic;
use app\modules\home\widgets\Active;
use app\modules\home\widgets\Recommend;
use app\modules\home\widgets\Advert;
use app\modules\home\widgets\Contact;
use app\modules\home\widgets\Qrcode;


$this->params['isHome'] = true;
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

        <!-- 更多文章 -->
        <?php
            try{
                echo More::widget();
            }catch (Exception $e){
                echo '获取更多文章失败。';
            }
        ?>
    </div>
    <!-- end posts -->

    <!-- Sidebar -->
    <aside class="col-lg-4 sidebar sidebar--right">
        <!-- 公众号 -->
        <!--<div class="widget widget_mc4wp_form_widget text-center">
            <img width="100%" src="static/assets/img/blog/rqcode.png" alt="关注微信公众号" class="mb-3">
            <h4 class="widget-title">扫码直接下载APP</h4>
        </div>-->

        <?php
        try{
            //二维码
            echo Qrcode::Widget([
                 'image' => 'static/assets/img/blog/rqcode.png'
            ]);

            //热门话题
            echo HotTopic::widget();

            //热门话题
            echo Active::widget();

            //推荐文章
            echo Recommend::widget();

            //广告
            echo Advert::Widget();

            //作者社交账号
            echo Contact::Widget();

        }catch (Exception $e){
            throw $e;
        }
        ?>

    </aside>
    <!-- end sidebar -->
</div>
<!-- end content -->


