<?php

/* @var $this yii\web\View */
$this->title = '关于我';
?>
<!-- Content -->
<div class="row">
    <!-- post content -->
    <div class="col-lg-12 blog__content mb-30">
        <div class="row justify-content-md-center title-wrap">
            <div class="col-md-8 center-block mb-30 ">
                <h2>关于我</h2>
            </div>
        </div>
        <div class="row justify-content-md-center">
            <div class="col-md-8 center-block mb-30 ">
                <?= $this->params['base']['seo']['about'] ?: '暂无内容。'?>
            </div>
        </div>
    </div>
    <!-- end col -->
</div>
<!-- end content -->