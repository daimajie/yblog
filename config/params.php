<?php

return [
    'adminEmail' => 'admin@example.com',

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


];
