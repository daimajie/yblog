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
            'class' => 'app\modules\admin\modules\content\Module',
        ],
        //用户管理
        'member' => [
            'class' => 'app\modules\admin\modules\member\Module',
        ],
        //用户管理
        'motion' => [
            'class' => 'app\modules\admin\modules\motion\Module',
        ],
        //用户管理
        'rbac' => [
            'class' => 'app\modules\admin\modules\rbac\Module',
        ],
    ],
    'components' => [

    ],
    'params' => [

    ],
];