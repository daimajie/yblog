<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/3/8
 * Time : 20:58
 */

namespace app\commands;
use app\components\rules\ArticleRule;
use app\components\rules\TopicRule;
use Yii;
use yii\console\Controller;


class RbacController extends Controller
{
    private $authManager;

    private $topicRule;
    private $articleRule;

    private $author;
    private $admin;

    public function init()
    {
        parent::init();

        $this->authManager = $auth = Yii::$app->authManager;

        //先清空所有权限数据
        $auth->removeAll();
    }


    public function actionInit()
    {

        //创建角色
        $this->addRoles();

        //添加规则
        $this->addRules();

        //添加话题操作路由
        $this->addTopicRouter();


        //添加文章操作权限路由
        $this->addArticleRouter();

        //添加标签操作路由
        $this->addTagRouter();

        //添加 后台管理路由
        $this->addAdminRouter();






        $this->authManager->assign($this->author, 2);
        $this->authManager->assign($this->author, 1);
        $this->authManager->assign($this->admin, 1);
    }

    /**
     * 添加角色
     */
    private function addRoles(){
        //创建作者
        $this->author = $author =  $this->authManager->createRole('作者');
        $this->authManager->add($author);

        //创建管理员
        $this->admin = $admin =  $this->authManager->createRole('管理员');
        $this->authManager->add($admin);
    }

    /**
     * 添加规则
     */
    private function addRules(){
        //添加规则
        $this->topicRule = $topic =  new TopicRule();
        $this->authManager->add($topic);

        $this->articleRule = $article =  new ArticleRule();
        $this->authManager->add($article);



    }

    /**
     * 添加后台管理路由
     */
    private function addAdminRouter(){
        // 添加 (添加后台管理路由) 权限
        $adminRouter = $this->authManager->createPermission('/admin/*');
        $adminRouter->description = '添加后台管理路由';
        $this->authManager->add($adminRouter);

        $this->authManager->addChild($this->admin, $adminRouter);
    }

    /**
     * 添加标签路由
     */
    private function addTagRouter(){

        // 添加 (操作标签路由) 权限
        $operateTag = $this->authManager->createPermission('/home/write/tag/*');
        $operateTag->description = '操作标签路由';
        $this->authManager->add($operateTag);

        $this->authManager->addChild($this->author, $operateTag);
    }

    /**
     * 添加文章操作路由权限
     */
    private function addArticleRouter(){
        // 添加 (文章图片上传路由) 权限
        $uploadArticle = $this->authManager->createPermission('/home/write/article/upload');
        $uploadArticle->description = '文章图片上传路由';
        $this->authManager->add($uploadArticle);

        // 添加 (搜索话题路由) 权限
        $selectArticle = $this->authManager->createPermission('/home/write/article/select');
        $selectArticle->description = '搜索话题路由';
        $this->authManager->add($selectArticle);

        // 添加 (话题文章列表路由) 权限
        $indexArticle = $this->authManager->createPermission('/home/write/article/index');
        $indexArticle->description = '话题文章列表路由';
        $indexArticle->ruleName = $this->topicRule->name;
        $this->authManager->add($indexArticle);

        // 添加 (文章详情路由) 权限
        $viewArticle = $this->authManager->createPermission('/home/write/article/view');
        $viewArticle->description = '文章详情路由';
        $viewArticle->ruleName = $this->articleRule->name;
        $this->authManager->add($viewArticle);

        // 添加 (创建文章路由) 权限
        $createArticle = $this->authManager->createPermission('/home/write/article/create');
        $createArticle->description = '创建文章路由';
        $createArticle->ruleName = $this->topicRule->name;
        $this->authManager->add($createArticle);

        // 添加 (修改文章路由) 权限
        $updateArticle = $this->authManager->createPermission('/home/write/article/update');
        $updateArticle->description = '修改文章路由';
        $updateArticle->ruleName = $this->articleRule->name;
        $this->authManager->add($updateArticle);

        // 添加 (删除文章路由) 权限
        $deleteArticle = $this->authManager->createPermission('/home/write/article/delete');
        $deleteArticle->description = '删除文章路由';
        $deleteArticle->ruleName = $this->articleRule->name;
        $this->authManager->add($deleteArticle);


        //作者拥有的路由权限
        $this->authManager->addChild($this->author, $uploadArticle);
        $this->authManager->addChild($this->author, $selectArticle);
        $this->authManager->addChild($this->author, $indexArticle);
        $this->authManager->addChild($this->author, $viewArticle);
        $this->authManager->addChild($this->author, $createArticle);
        $this->authManager->addChild($this->author, $updateArticle);
        $this->authManager->addChild($this->author, $deleteArticle);

    }


    /**
     * 添加话题操作路由权限
     */
    private function addTopicRouter(){
        // 添加 (话题图片上传路由) 权限
        $uploadTopic = $this->authManager->createPermission('/home/write/topic/upload');
        $uploadTopic->description = '话题图片上传路由';
        $this->authManager->add($uploadTopic);

        // 添加 (话题列表路由) 权限
        $indexTopic = $this->authManager->createPermission('/home/write/topic/index');
        $indexTopic->description = '话题列表路由';
        $this->authManager->add($indexTopic);

        // 添加 (话题创建路由) 权限
        $createTopic = $this->authManager->createPermission('/home/write/topic/create');
        $createTopic->description = '话题创建路由';
        $this->authManager->add($createTopic);

        // 添加 (话题修改路由) 权限
        $updateTopic = $this->authManager->createPermission('/home/write/topic/update');
        $updateTopic->description = '话题修改路由';
        $updateTopic->ruleName = $this->topicRule->name;
        $this->authManager->add($updateTopic);

        // 添加 (话题删除路由) 权限
        $deleteTopic = $this->authManager->createPermission('/home/write/topic/delete');
        $deleteTopic->description = '话题修改路由';
        $deleteTopic->ruleName = $this->topicRule->name;
        $this->authManager->add($deleteTopic);

        // 添加 (话题详情路由) 权限
        $viewTopic = $this->authManager->createPermission('/home/write/topic/view');
        $viewTopic->description = '话题详情路由';
        $viewTopic->ruleName = $this->topicRule->name;
        $this->authManager->add($viewTopic);

        // 添加 (话题文章列表路由) 权限
        $showTopic = $this->authManager->createPermission('/home/write/topic/show');
        $showTopic->description = '话题文章列表路由';
        $showTopic->ruleName = $this->topicRule->name;
        $this->authManager->add($showTopic);

        //作者包含的路由权限
        $this->authManager->addChild($this->author, $uploadTopic);
        $this->authManager->addChild($this->author, $indexTopic);
        $this->authManager->addChild($this->author, $createTopic);
        $this->authManager->addChild($this->author, $updateTopic);
        $this->authManager->addChild($this->author, $deleteTopic);
        $this->authManager->addChild($this->author, $viewTopic);
        $this->authManager->addChild($this->author, $showTopic);
    }




}