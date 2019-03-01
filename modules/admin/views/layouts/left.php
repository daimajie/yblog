<?php
use app\components\Helper;
use yii\helpers\Html;

$user = Yii::$app->user->identity;
$username = empty($user->nickname) ? $user->username : $user->nickname;

?>

<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= Helper::avatar($user->image)?>" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info" >
                <p><?= $username?></p>
                <?= Html::a(
                    '安全退出 <i class="glyphicon glyphicon-log-out"></i>',
                    ['/index/logout'],
                    ['data-method' => 'post']
                ) ?>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form clearfix">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="搜索..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    ['label' => 'Menu y-blog', 'options' => ['class' => 'header']],
                    [
                        'label' => '内容管理',
                        'icon' => 'bookmark-o',
                        'url' => '#',
                        'items' => [
                            ['label' => '分类管理', 'icon' => '', 'url' => ['/admin/content/category'],],
                            ['label' => '话题管理', 'icon' => '', 'url' => ['/admin/content/topic'],],
                            ['label' => '文章管理', 'icon' => '', 'url' => ['/admin/content/article'],],
                        ],
                    ],
                    [
                        'label' => '用户管理',
                        'icon' => 'bookmark-o',
                        'url' => '#',
                        'items' => [
                            ['label' => '会员管理', 'icon' => '', 'url' => ['/admin/member/user'],],
                        ],
                    ],
                    [
                        'label' => '行为管理',
                        'icon' => 'bookmark-o',
                        'url' => '#',
                        'items' => [
                            ['label' => '评论管理', 'icon' => '', 'url' => ['/admin/motion/comment'],],
                        ],
                    ],


                    /*['label' => 'Menu Yii2.0', 'options' => ['class' => 'header']],
                    ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii']],
                    ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug']],

                    [
                        'label' => 'Mailbox',
                        'icon' => 'envelope-o',
                        'url' => ['/mailbox'],
                        'template'=>'<a href="{url}">{icon} {label}<span class="pull-right-container"><small class="label pull-right bg-yellow">123</small></span></a>'
                    ],*/
                ],
            ]
        ) ?>

    </section>

</aside>
