<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/3/8
 * Time : 18:32
 */

$this->title = Yii::$app->name . ' 控制台';
?>
<div class="box box-primary">
    <div class="box-body table-responsive no-padding">
        <section class="content">
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="fa fa-envelope-o"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">用户留言</span>
                            <span class="info-box-number"><?= $count['messageCount']?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="fa fa-users"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">注册用户</span>
                            <span class="info-box-number"><?= $count['userCount']?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-yellow"><i class="fa fa-file-text"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">博客文章</span>
                            <span class="info-box-number"><?= $count['articleCount']?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-red"><i class="fa fa-book"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">专题文章</span>
                            <span class="info-box-number"><?= $count['topicCount']?></span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

