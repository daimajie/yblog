<?php
use yii\helpers\Url;
use yii\helpers\Html;
use app\assets\MainAsset;
use app\components\Helper;
use app\modules\home\controllers\BaseController;
use yii\widgets\ActiveForm;
use yii\widgets\Menu;
use app\components\ViewHelper;


MainAsset::register($this);

/*variables*/
$isGuest = Yii::$app->user->isGuest;
if(!$isGuest){
    $user = Yii::$app->user->identity;
    $avatar = $user->image;
    $username = empty($user->nickname) ? $user->username : $user->nickname;
    $isAuthor = $user->author >= 0;
}
/*基本数据 菜单 SEO 等*/
$base = $this->params['base'];

$router = ''; //Url::to() 方法中的路由参数 用来判断是否是首页
$showHeader = isset($this->params['showHeader']) ? $this->params['showHeader'] : true;
?>


<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <?= Html::csrfMetaTags() ?>
    <meta name="keywords" content="<?= $base['seo']['keywords']?>">
    <meta name="description" content="<?= $base['seo']['description']?>" />
    <title><?= Html::encode($this->title) . ' ' . $base['seo']['name']?></title>
    <?php $this->head() ?>
</head>

<body class="bg-light">
<?php $this->beginBody() ?>

<!-- Preloader -->
<div class="loader-mask">
    <div class="loader">
        <div></div>
    </div>
</div>

<!-- Bg Overlay -->
<div class="content-overlay"></div>

<!-- Sidenav -->
<header class="sidenav" id="sidenav">

    <!-- close -->
    <div class="sidenav__close">
        <button class="sidenav__close-button" id="sidenav__close-button" aria-label="close sidenav">
            <i class="ui-close sidenav__close-icon"></i>
        </button>
    </div>

    <!-- Nav -->
    <nav class="sidenav__menu-container">

        <?php if(!$isGuest):?>
        <!-- user-info -->
        <div class="media user-info">
            <div class="media-left">
                <a href="javascript:void(0);">
                    <img class="media-object" src="<?= Helper::avatar($avatar)?>">
                </a>
            </div>
            <div class="media-body user-body">
                <ul class="sidenav__menu" role="menubar">
                    <li>
                        <a href="JavaScript:void(0);" class="sidenav__menu-link user-name"><?= $username?></a>
                        <button class="sidenav__menu-toggle user-action" aria-haspopup="true" aria-label="Open dropdown"><i class="ui-arrow-down"></i></button>
                        <ul class="sidenav__menu-dropdown user-nav">
                            <li><a href="<?= Url::to(['/home/member/user/setting'])?>" class="sidenav__menu-link">账号设置</a></li>
                            <?php if($isAuthor):?>
                            <li><a href="<?= Url::to(['/home/member/author/index','id'=>$user->id])?>" class="sidenav__menu-link">个人主页</a></li>
                            <li><a href="<?= Url::to(['/home/write/topic/index'])?>" class="sidenav__menu-link">写作中心</a></li>
                            <?php endif;?>
                            <li><?= Html::a('安全退出',['/home/index/logout'], ['data-method' => 'post','class'=>'sidenav__menu-link'])?></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <?php else:?>
        <div class="user-info">
          <a href="<?= Url::to(['index/login'])?>" class="btn btn-sm btn-light">
            <span>登录</span>
          </a>
          <a href="<?= Url::to(['index/logout'])?>" class="btn btn-sm btn-light">
            <span>注册</span>
          </a>
        </div>
        <?php endif?>

        <ul class="sidenav__menu clearfix" role="menubar">
            <!-- Categories -->
            <?php if(!empty($base[BaseController::CACHE_CATEGORY_LIST])):?>
            <li>
                <a href="javascript:void(0);" class="sidenav__menu-link">内容分类</a>
                <button class="sidenav__menu-toggle" aria-haspopup="true" aria-label="Open dropdown"><i class="ui-arrow-down"></i></button>
                <ul class="sidenav__menu-dropdown">
                    <?php
                    if(!empty($this->params['isHome'])){
                        $router = Yii::$app->defaultRoute;
                    }else{
                        $router = '/home/content/topic/index';
                    }
                    foreach($base[BaseController::CACHE_CATEGORY_LIST] as $key => $val):
                        ?>
                    <li><a href="<?= Url::to([$router, 'category_id'=>$key])?>" class="sidenav__menu-link"><?= $val?></a></li>
                    <?php endforeach;?>
                </ul>
            </li>
            <?php endif;?>

            <!-- nav -->
            <li>
                <a href="/" class="sidenav__menu-link sidenav__menu-link-category sidenav__menu-link--orange"><i class="ui-home"></i> 网站首页</a>
            </li>
            <li>
                <a href="<?= Url::to(['/home/content/topic/index'])?>" class="sidenav__menu-link sidenav__menu-link-category sidenav__menu-link--blue"><small>HOT</small> 热门话题</a>
            </li>
            <li>
                <a href="<?= Url::to(['/home/index/about'])?>" class="sidenav__menu-link sidenav__menu-link-category sidenav__menu-link--salad"><i class="ui-author"></i> 关于</a>
            </li>
            <li>
                <a href="<?= Url::to(['/home/motion/contact/create'])?>" class="sidenav__menu-link sidenav__menu-link-category sidenav__menu-link--purple"><i class="ui-email"></i> 联系</a>
            </li>
        </ul>
    </nav>

    <div class="socials sidenav__socials">
        <a class="social social-facebook" href="#" target="_blank" aria-label="facebook">
            <i class="ui-facebook"></i>
        </a>
        <a class="social social-twitter" href="#" target="_blank" aria-label="twitter">
            <i class="ui-twitter"></i>
        </a>
        <a class="social social-google-plus" href="#" target="_blank" aria-label="google">
            <i class="ui-google"></i>
        </a>
        <a class="social social-youtube" href="#" target="_blank" aria-label="youtube">
            <i class="ui-youtube"></i>
        </a>
        <a class="social social-instagram" href="#" target="_blank" aria-label="instagram">
            <i class="ui-instagram"></i>
        </a>
    </div>
</header> <!-- end sidenav -->

<main class="main oh" id="main">

    <!-- Navigation -->
    <header class="nav">

        <div class="nav__holder nav--sticky">
            <div class="container relative">
                <div class="flex-parent">

                    <!-- Side Menu Button -->
                    <button class="nav-icon-toggle" id="nav-icon-toggle" aria-label="Open side menu">
                      <span class="nav-icon-toggle__box">
                        <span class="nav-icon-toggle__inner"></span>
                      </span>
                    </button> <!-- end Side menu button -->

                    <!-- Mobile logo -->
                    <a href="<?= Url::home([])?>" class="logo logo--mobile d-lg-none">
                        <img class="logo__img" src="<?= ViewHelper::showImage($base['seo']['mobile_logo'])?>" alt="logo">
                    </a>

                    <nav class="flex-child nav__wrap d-none d-lg-block">
                        <?php

                        $tmp = [];
                        foreach ($base[BaseController::CACHE_CATEGORY_LIST] as $key => $val){
                            $tmp[] = [
                                'label'  => $val,
                                'url'    => [$router, 'category_id'=>$key]
                            ];
                        }

                        echo Menu::widget([
                            'options' => [
                                    'class' => 'nav__menu'
                            ],
                            'submenuTemplate' => "\n<ul class='nav__dropdown-menu'>\n{items}\n</ul>\n",
                            'items' => [
                                ['label' => '首页', 'url' => [Yii::$app->defaultRoute], ['options'=>['class'=>'active']]],
                                [
                                    'label' => '内容分类',
                                    'url'=>'javascript:void(0);',
                                    'options'=>['class'=>'nav__dropdown'],
                                    'items'=>$tmp
                                ],
                                ['label' => '热门话题', 'url' => ['/home/content/topic/index']],
                                ['label' => '关于', 'url' => ['/home/index/about']],
                                ['label' => '联系', 'url' => ['/home/motion/contact/create']],

                            ],
                        ]);
                        ?>
                    </nav>

                    <!-- end nav-wrap -->

                    <!-- Nav Right -->
                    <div class="nav__right nav--align-right d-lg-flex">
                        <!-- Search -->
                        <div class="nav__right-item nav__search">
                            <a href="#" class="nav__search-trigger" id="nav__search-trigger">
                                <i class="ui-search nav__search-trigger-icon"></i>
                            </a>
                            <div class="nav__search-box" id="nav__search-box">
                                <?php ActiveForm::begin([
                                    'options' => ['class'=>'nav__search-form'],
                                    'method' => 'get',
                                    'action' => ['/home/index/search']
                                ])?>
                                    <?= Html::input('text', 'title', '', [
                                        'class'=>'nav__search-input',
                                        'autocomplete'=>"off"
                                    ])?>
                                    <button type="submit" class="search-button btn btn-lg btn-color btn-button">
                                        <i class="ui-search nav__search-icon"></i>
                                    </button>
                                <?php ActiveForm::end();?>
                            </div>

                        </div>

                    </div> <!-- end nav right -->

                </div> <!-- end flex-parent -->
            </div> <!-- end container -->

        </div>
    </header>
    <!-- end navigation -->

    <!-- Header -->
    <?php
    $mt = 'mt-40';
    if(!isset($showHeader) || $showHeader):
        $mt = 'mt-0';
        ?>
    <div class="header">
        <div class="container">
            <div class="flex-parent align-items-center">

                <!-- Logo -->
                <a href="<?= Url::home([])?>" class="logo d-none d-lg-block">
                    <img class="logo__img" src="<?= ViewHelper::showImage($base['seo']['pc_logo'])?>" alt="logo">
                </a>

                <!-- Ad Banner 728 -->
                <div class="text-center">
                    <a href="#">
                        <img src="static/assets/img/blog/placeholder_leaderboard.jpg" alt="">
                    </a>
                </div>

            </div>
        </div>
    </div>
    <?php endif;?>
    <!-- end header -->


    <div class="main-container container <?= $mt?>" id="main-container">

        <!-- Content -->
        <?= $content ?>
        <!-- end content -->

    </div>
    <!-- end main container -->

    <!-- Footer -->
    <footer class="footer footer--dark">
        <div class="container">
            <div class="footer__widgets">
                <div class="row">

                    <div class="col-lg-3 col-md-6">
                        <div class="widget">
                            <a href="index.html">
                                <img src="img/logo_mobile.png" srcset="img/logo_mobile.png 1x, img/logo_mobile@2x.png 2x" class="logo__img" alt="">
                            </a>
                            <p class="mt-20">We bring you the best Premium WordPress Themes. Deliver smart websites faster with this amazing theme. We care about our buyers.</p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <h4 class="widget-title">最新回复文章</h4>
                        <ul class="post-list-small">
                            <li class="post-list-small__item">
                                <article class="post-list-small__entry clearfix">
                                    <div class="post-list-small__img-holder">
                                        <div class="thumb-container thumb-75">
                                            <a href="single-post.html">
                                                <img data-src="static/assets/img/blog/popular_post_1.jpg" src="static/assets/img/empty.png" alt="" class="lazyload">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="post-list-small__body">
                                        <h3 class="post-list-small__entry-title">
                                            <a href="single-post.html">Google is fixing its troubling burger emoji in Android 8.1</a>
                                        </h3>
                                        <ul class="entry__meta">
                                            <li class="entry__meta-date">
                                                <i class="ui-date"></i>
                                                21 October, 2017
                                            </li>
                                        </ul>
                                    </div>
                                </article>
                            </li>
                            <li class="post-list-small__item">
                                <article class="post-list-small__entry clearfix">
                                    <div class="post-list-small__img-holder">
                                        <div class="thumb-container thumb-75">
                                            <a href="single-post.html">
                                                <img data-src="static/assets/img/blog/popular_post_2.jpg" src="static/assets/img/empty.png" alt="" class="lazyload">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="post-list-small__body">
                                        <h3 class="post-list-small__entry-title">
                                            <a href="single-post.html">How Meditation Can Transform Your Business</a>
                                        </h3>
                                        <ul class="entry__meta">
                                            <li class="entry__meta-date">
                                                <i class="ui-date"></i>
                                                21 October, 2017
                                            </li>
                                        </ul>
                                    </div>
                                </article>
                            </li>
                        </ul>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="widget widget__newsletter">
                            <h4 class="widget-title">关注我</h4>

                            <div class="socials mb-20">
                                <a href="#" class="social social-facebook" aria-label="facebook"><i class="ui-facebook"></i></a>
                                <a href="#" class="social social-twitter" aria-label="twitter"><i class="ui-twitter"></i></a>
                                <a href="#" class="social social-google-plus" aria-label="google+"><i class="ui-google"></i></a>
                                <a href="#" class="social social-youtube" aria-label="youtube"><i class="ui-youtube"></i></a>
                                <a href="#" class="social social-instagram" aria-label="instagram"><i class="ui-instagram"></i></a>
                            </div>

                            <form class="mc4wp-form" method="post">
                                <div class="mc4wp-form-fields">
                                    <p>
                                        <input type="email" name="EMAIL" placeholder="你的邮箱" required="">
                                    </p>
                                    <p>
                                        <input type="submit" class="btn btn-lg btn-color" value="订阅">
                                    </p>
                                </div>
                            </form>

                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="widget widget_nav_menu">
                            <h4 class="widget-title">站点链接</h4>
                            <ul>
                                <li><a href="about.html">关于我</a></li>
                                <li><a href="contact.html">联系我</a></li>
                                <li><a href="categories.html">首页</a></li>
                                <li><a href="shortcodes.html">全部话题</a></li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div> <!-- end container -->

        <div class="footer__bottom">
            <div class="container">
                <div class="row">
                    <div class="col-lg-7 order-lg-2 text-right text-md-center">
                        <div class="widget widget_nav_menu">
                            <ul>
                                <li><a href="#">Terms</a></li>
                                <li><a href="#">Privacy</a></li>
                                <li><a href="#">Advertise</a></li>
                                <li><a href="#">Affiliates</a></li>
                                <li><a href="#">Newsletter</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-5 order-lg-1 text-md-center">
              <span class="copyright">
                &copy; 2018 | <a href="https://github.com/daimajie/yblog">y-blog</a>
              </span>
                    </div>
                </div>

            </div>
        </div> <!-- end bottom footer -->
    </footer> <!-- end footer -->

    <div id="back-to-top">
        <a href="#top" aria-label="Go to top"><i class="ui-arrow-up"></i></a>
    </div>

</main> <!-- end main-wrapper -->


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>