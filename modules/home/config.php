<?php
/**
 * Created by PhpStorm.
 * User: daimajie
 * Date: 2019/1/20
 * Time: 13:17
 */
return [
    'layout' => 'main',

    'modules' => [
        //内容管理
        'content' => [
            'class' => 'app\modules\home\modules\content\Module',
        ],
        //活动记录
        'motion' => [
            'class' => 'app\modules\home\modules\motion\Module',
        ],
        //用户中心
        'member' => [
            'class' => 'app\modules\home\modules\member\Module',
        ],
        //写作中心
        'write' => [
            'class' => 'app\modules\home\modules\write\Module',
        ],

    ],
    'components' => [

    ],
    'params' => [

    ],
];