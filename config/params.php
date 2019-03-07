<?php

return [
    'adminEmail' => 'daimajie@qq.com',
    //站点信息
    'seo' => [
        'name' => 'y-blog',
        'keywords' => '网络写作社区,文章发布,网络博客',
        'description' => '这是一个基于话题的为网络写手提供的写作平台，包含文学 长篇故事 短篇故事散文 生活记事等作品。',
        'logo' => ['pc'=>'static/assets/img/logo.png','mobile'=>'static/assets/img/logo_mobile.png'],
        'qrcode' => 'static/assets/img/blog/rqcode.png',
    ],

    //后台应用参数
    'admin_app' => [
        'name' => 'y-blog',
    ],

    //上传配置
    'upload' => [
        'upRoot' => 'static/uploaded', //基于web根目录
        'tmpPath' => 'static/uploaded/tmp' //临时路径(可解决部分垃圾图片)
    ],

    //标签设置
    'tag' => [
        'limit' => 35, //每个话题允许创建多少个标签,
        'createLimit' => 3, //创建文章时可同时创建多少个新标签
        'articleLimit' => 3, //一篇文章最多可关联三个标签( !!!注意： 该数应该大于等于createLimit )
    ],

    //邮箱验证码session key
    'email_key' => 'email_key',

    //密码重置token有效时间
    'passwordResetTokenExpire' => 60 * 30,

    //用户配置
    'user_properties' => [
        'defaultAvatar' => '/static/assets/img/avatar.jpg',
    ],

    //话题设置
    'topic' => [
        'secrecy_limit' => 5,
        'active_limit' => 5
    ]


];
