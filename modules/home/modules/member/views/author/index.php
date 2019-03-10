<?php
use app\components\ViewHelper;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\helpers\Url;
use app\models\content\Topic;
use app\modules\home\widgets\Qrcode;
use app\modules\home\widgets\Contact;
use app\modules\home\widgets\TopicList;


/*$model 用户模型*/
/*$dataProvide 文章数据提供者*/

//搜索的属性
$title = trim(Yii::$app->request->get('title', ''));

$this->title = ViewHelper::username($model->username, $model->nickname);
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
                    <?php
                    if(!empty($model->profile->photo))
                        echo Html::img(ViewHelper::showImage($model->profile->photo),[
                                'class' => 'img-thumbnail'
                        ])
                    ?>
                </div>
                <h5><i class="ui-author"></i> <?= ViewHelper::username($model->username, $model->nickname)?></h5>
                <p><small> 文章 - <?= $model->author?>  /  话题 - <?= $topicCount?> </small></p>
                <p class="mb-3"><?= empty($model->profile->intro) ? '博主好懒～什么也没留下！' : Html::encode($model->profile->intro)?></p>
            </div>
        </div>

        <?php
        //作者社交账号
        //echo Contact::Widget();


        //二维码
        echo Qrcode::Widget([
            'image' => !empty($model->profile->qrcode) ? ViewHelper::showImage($model->profile->qrcode) : '',
            'title' => '打赏作者'
        ]);

        //私密话题
        echo TopicList::Widget([
            'user_id' => $model->id,
            'secrecy' => true,
            'title' => '私密话题(仅作者可见)'
        ]);

        //热门话题
        echo TopicList::Widget([
            'user_id' => $model->id,
            'secrecy' => false
        ]);
        ?>

    </aside>
    <!-- end sidebar -->

</div>
<!-- end content -->