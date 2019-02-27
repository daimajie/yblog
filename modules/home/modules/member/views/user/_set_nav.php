<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/2/26
 * Time : 16:10
 */
use yii\helpers\Url;
use yii\widgets\Menu;
?>
<nav>
    <?php
    echo Menu::widget([
        'activeCssClass' => 'active',
        'items' => [
            ['label' => '编辑资料', 'url' => ['user/setting'],'template' => '<a class="sidenav__menu-link sidenav__menu-link--violet" href="{url}">{label}</a>'],
            ['label' => '修改头像', 'url' => ['user/avatar'],'template' => '<a class="sidenav__menu-link sidenav__menu-link--blue" href="{url}">{label}</a>'],
            ['label' => '修改密码', 'url' => ['user/password'],'template' => '<a class="sidenav__menu-link sidenav__menu-link--red" href="{url}">{label}</a>'],
            ['label' => '修改邮箱', 'url' => ['user/email'],'template' => '<a class="sidenav__menu-link sidenav__menu-link--purple" href="{url}">{label}</a>'],
            ['label' => '个人主页', 'url' => ['/home/member/author/index', 'id'=>Yii::$app->user->id],'template' => '<a class="sidenav__menu-link sidenav__menu-link--orange" href="{url}">{label}</a>'],

        ],
    ]);
    ?>
</nav>
