<?php
use yii\helpers\Url;
use yii\helpers\Html;
use app\assets\MainAsset;
use app\components\Helper;
use app\modules\home\controllers\BaseController;
use yii\widgets\ActiveForm;



MainAsset::register($this);

/*variables*/
$isGuest = Yii::$app->user->isGuest;
if(!$isGuest){
    $user = Yii::$app->user->identity;
    $avatar = $user->image;
    $username = empty($user->nickname) ? $user->username : $user->nickname;
}
/*基本数据 菜单 SEO 等*/
$base = $this->params['base'];
$router = ''; //Url::to() 方法中的路由参数 用来判断是否是首页
?>


<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
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
                            <li><a href="<?= Url::to(['member/center'])?>" class="sidenav__menu-link">个人中心</a></li>
                            <li><a href="<?= Url::to(['member/write'])?>" class="sidenav__menu-link">写文章</a></li>
                            <li><a href="<?= Url::to(['admin/index'])?>" class="sidenav__menu-link">控制台</a></li>
                            <li><a href="<?= Url::to(['index/logout'])?>" class="sidenav__menu-link">退出</a></li>
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
                        $router = '';
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
                <a href="<?= Url::to(['/home/index/index'])?>" class="sidenav__menu-link sidenav__menu-link-category sidenav__menu-link--orange"><i class="ui-home"></i> 网站首页</a>
            </li>
            <li>
                <a href="<?= Url::to(['/home/content/topic/index'])?>" class="sidenav__menu-link sidenav__menu-link-category sidenav__menu-link--blue"><small>HOT</small> 热门话题</a>
            </li>
            <li>
                <a href="<?= Url::to(['/home/index/about'])?>" class="sidenav__menu-link sidenav__menu-link-category sidenav__menu-link--salad"><i class="ui-author"></i> 关于我</a>
            </li>
            <li>
                <a href="<?= Url::to(['/home/index/contact'])?>" class="sidenav__menu-link sidenav__menu-link-category sidenav__menu-link--purple"><i class="ui-email"></i> 联系我</a>
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
                    <a href="index.html" class="logo logo--mobile d-lg-none">
                        <img class="logo__img" src="static/assets/img/logo_mobile.png" srcset="static/assets/img/logo_mobile.png 1x, static/assets/img/logo_mobile@2x.png 2x" alt="logo">
                    </a>

                    <!-- Nav-wrap -->
                    <nav class="flex-child nav__wrap d-none d-lg-block">
                        <ul class="nav__menu">
                            <li class="active">
                                <a href="<?= Url::to(['/home/index/index'])?>">首页</a>
                            </li>
                            <?php if(!empty($base[BaseController::CACHE_CATEGORY_LIST])):?>
                                <li class="nav__dropdown">
                                    <a href="javascript:void(0);">内容分类</a>
                                    <ul class="nav__dropdown-menu">
                                        <?php foreach($base[BaseController::CACHE_CATEGORY_LIST] as $key => $val):?>
                                            <li><a href="<?= Url::to([$router, 'category_id'=>$key])?>"><?= $val?></a></li>
                                        <?php endforeach;?>
                                    </ul>
                                </li>
                            <?php endif;?>
                            <li>
                                <a href="<?= Url::to(['/home/content/topic/index'])?>">热门话题</a>
                            </li>
                            <li>
                                <a href="<?= Url::to(['/home/index/about'])?>">关于我</a>
                            </li>
                            <li>
                                <a href="<?= Url::to(['/home/index/contact'])?>">联系我</a>
                            </li>
                        </ul> <!-- end menu -->
                    </nav> <!-- end nav-wrap -->

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
    </header> <!-- end navigation -->

    <!-- Header -->
    <div class="header">
        <div class="container">
            <div class="flex-parent align-items-center">

                <!-- Logo -->
                <a href="index.html" class="logo d-none d-lg-block">
                    <img class="logo__img" src="static/assets/img/logo.png" srcset="static/assets/img/logo.png 1x, static/assets/img/logo@2x.png 2x" alt="logo">
                </a>

                <!-- Ad Banner 728 -->
                <div class="text-center">
                    <a href="#">
                        <img src="static/assets/img/blog/placeholder_leaderboard.jpg" alt="">
                    </a>
                </div>

            </div>
        </div>
    </div> <!-- end header -->


    <div class="main-container container mt-40" id="main-container">

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