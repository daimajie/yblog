<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/2/19
 * Time : 12:21
 */
use yii\widgets\ListView;
use yii\helpers\Html;



$this->title = '热门话题';


//$model 搜索模型
//$category_items 分类列表数据
//$dataProvider 话题列表数据提供者
//$params 搜索参数
?>
<!-- Content -->
<div class="row">
    <!-- post content -->
    <div class="col-lg-12 blog__content mb-30">

        <div class="title-wrap">
            <div class="row">
                <div class="col-md-4">
                    <h3><?= Html::encode($this->title)?></h3>
                </div>
                <div class="col-md-8">
                    <?= Html::beginForm(['index'], 'get') ?>
                    <div class="row">
                        <div class="col-md-6">
                            <?= Html::dropDownList('category_id', isset($params['category_id'])?$params['category_id']:'', $category_items, [
                                'prompt'=>'选择分类'
                            ]) ?>
                        </div>
                        <div class="col-md-6">
                            <div class="search-form">
                                <?= Html::input('text', 'name', isset($params['name'])?trim($params['name']):'', [
                                    'placeholder'=>'话题名称',
                                    'autocomplete'=>"off"
                                ]) .
                                Html::submitButton('<i class="ui-search search-icon"></i>', [
                                        'class' => 'search-button btn btn-lg btn-color btn-button'
                                ])
                                ?>
                            </div>
                        </div>
                    </div>
                    <?= Html::endForm() ?>
                </div>
            </div>
        </div>


        <?php
        //话题列表
        echo ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '_topic',
            'layout' => "<div class='row mb-30'>{items}</div>{pager}",
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
    </div>
    <!-- end col -->
</div>
<!-- end content -->