<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
use app\modules\home\models\content\SearchArticle;
use app\modules\home\widgets\Qrcode;

$this->title = $name;
?>
<!-- Content -->
<div class="row">

    <!-- post content -->
    <div class="col-lg-8 blog__content mb-30">

        <h1 class="mb-20"><?= Html::encode($this->title) ?></h1>
        <div class="alert alert-danger" role="alert">
            <?= nl2br(Html::encode($exception->getMessage())) ?>
        </div>
        <p><small><?= $exception->statusCode === 404 ? '抱歉，找不到您要查找的页面。尝试搜索最佳匹配项或浏览以下链接：' : ''?></small></p>
        <p class="font-weight-normal"><small>如果您认为这是服务器错误，<a href="<?= Url::to(['index/contact'])?>">请联系我们</a>。谢谢您。</small></p>

        <?php
        if($exception->statusCode === 404){
            echo Html::beginForm(['search'], 'get',[
                'class'=>"search-form mb-20",
            ]);
            echo Html::input('text', 'title', '', [
                    'placeholder'=>'文章标题',
                    'autocomplete'=>"off"
                ]) .
                Html::submitButton('<i class="ui-search search-icon"></i>', [
                    'class' => 'search-button btn btn-lg btn-color btn-button'
                ]);
            echo Html::endForm();


            //话题列表
            try{
                echo ListView::widget([
                    'dataProvider' => (new SearchArticle())->search(['isError'=>true]),
                    'itemView' => '@app/modules/home/modules/content/views/topic/_article',
                    'layout' => "<div class='row mb-30'>{items}</div>",
                    'options' => [
                        'tag' => false
                    ],
                    'itemOptions' => [
                        'tag' => false
                    ],
                    'summaryOptions' => [
                        'class'=>'pull-right'
                    ],

                ]);
            }catch (\Exception $e){

            }
        }
        ?>

    </div> <!-- end col -->

    <!-- Sidebar -->
    <aside class="col-lg-4 sidebar sidebar--right">

        <!--qrcode-->
        <?php
        try{
            //二维码
            echo Qrcode::Widget([
                'image' => $this->params['base']['seo']['qrcode']
            ]);
        }catch (\yii\base\Exception $e){

        }
        ?>

    </aside>
    <!-- end sidebar -->
</div>
<!-- end content -->
