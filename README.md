###基于Yii2.0框架开发。

前台
-   RBAC权限模块、
-   用户中心，
-   写作中心，
-   作者主页
-   用户登录注册密码找回等...

后台    
-    内容管理
        分类，话题(回收站 审核功能)，标签，文章(回收站 审核功能)
-    用户管理
        用户添加 角色指派
-    用户行为
        评论管理 留言管理
-    RBAC
        添加权限 添加角色 规则管理
-    系统设置
        SEO 缓存管理 日志 广告 
    
细节
-   每个用户可创建5个私密话题 (只自己可见), 同时至多可有5个连载的公开话题(设为完结后再可创建新话题), 并且每个话题下标签都是相互独立的，
通过审核的话题，读者可见。
-   每个话题下标签可创建指定多个，话题间独立的。
-   文章有审核 回收站 功能。
-   安装后有两个角色 管理员和作者 可给读者指派角色 成为作者或管理。
-   大量的使用了小部件自定义小部件，及缓存。





### 安装

1 下载到本地:

~~~
git clone https://github.com/daimajie/yblog.git blog
~~~

2 安装依赖（进入项目目录执行如下命令）

~~~
php composer.phar install
~~~

3 配置数据库 及邮箱

```php
// config/db.php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=***',
    'username' => '***',
    'password' => '***',
    'charset' => 'utf8',
    'tablePrefix' => '',

    // Schema cache options (for production environment)
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 60,
    'schemaCache' => 'cache',
];

// config/web.php
'mailer' => [
        'class' => 'yii\swiftmailer\Mailer',
        'useFileTransport' => false,
        'transport' => [
            'class' => 'Swift_SmtpTransport',
            'host' => 'smtp.qq.com',
            'username' => '***@qq.com',
            'password' => '******',
            'port' => '465',
            'encryption' => 'ssl',
        ],
        'messageConfig'=>[
            'charset'=>'UTF-8',
        ],
    ],
```

4 初始化权限

~~~
./yii rbac/init  (window 去掉 './') 
~~~


5 添加用户(管理员及作者) 按照提示输入信息即可
~~~
./yii member/add-admin  (window 去掉 './') 
./yii member/add-author  (window 去掉 './') 
~~~

6 添加虚拟主机访问 web目录即可 获指定web目录为网站根目录