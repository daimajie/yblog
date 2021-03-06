<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/2/21
 * Time : 10:51
 */
use yii\helpers\Url;
use app\assets\LayerAsset;
use app\assets\MainAsset;

LayerAsset::register($this);
MainAsset::addScript($this,'/static/libs/art-template/template-web.js');
?>
<div id="comment_wrap">

    <!-- Comments -->
    <div id="comment_list" class="entry-comments mt-30">
        <div class="text-center"><b>Loading...</b></div>
    </div>
    <!-- end comments -->

    <!-- Comment Form -->
    <div id="respond" class="comment-respond">
        <div class="title-wrap">
            <h6 class="comment-respond__title uppercase">评论文章</h6>
        </div>
        <form id="form" class="comment-form" method="post" action="#">
            <p class="comment-form-comment">
                <!-- <label for="comment">Comment</label> -->
                <textarea id="comment_input" name="comment" rows="5" required="required" placeholder="评论内容..."></textarea>
            </p>

            <p class="comment-form-submit">
                <input type="button" class="btn btn-lg btn-color btn-button" value="提交评论" id="comment_btn">
                <input type="reset" class="btn btn-lg btn-button" value="重置">
            </p>

        </form>
    </div>
    <!-- end comment form -->
</div>

<!--template-->

<script id="comments_tpl" type="text/html">
    <div class="title-wrap mt-40">
        <h6 class="uppercase">评论数 - {{count}}</h6>
    </div>
    {{if comments}}
    <ul class="comment-list">
        {{each comments $val $key}}
        <li class="comment parent-comment" data-parent-id="{{@ $val.id}}">
            <div class="comment-body">
                <div class="comment-avatar">
                    <a href="{{@ $val.user.index}}">
                    <img width="50" height="50" src="{{@ $val.user.image}}">
                    </a>
                </div>
                <div class="comment-text">
                    <h6 class="comment-author"><a href="{{@ $val.user.index}}">{{@ $val.user.username}}</a></h6>
                    <div class="comment-metadata">
                        <a href="javascript:void(0);" class="comment-date">{{@ $val.created_at}}</a>
                    </div>
                    <p>{{@ $val.content}}</p>
                    <a href="javascript:void(0);" class="comment-reply reply"><small>回复</small></a>
                    {{if $val.owner}}
                    <a data-id="{{@ $val.id}}" href="javascript:void(0);" class="comment-reply delete"><small>删除</small></a>
                    {{/if}}
                </div>
            </div>
            {{if $val.replys}}
            <ul class="children">
                {{each $val.replys $v $k}}
                <li class="comment">
                    <div class="comment-body">
                        <div class="comment-avatar">
                            <img width="50" height="50" src="{{@ $v.user.image}}">
                        </div>
                        <div class="comment-text">
                            <h6 class="comment-author">{{@ $v.user.username}}</h6>
                            <div class="comment-metadata">
                                <a href="#" class="comment-date">{{@ $v.created_at}}</a>
                            </div>
                            <p>{{@ $v.content}}</p>
                            <a href="javascript:void(0);" class="comment-reply reply"><small>回复</small></a>
                            {{if $v.owner}}
                            <a data-id="{{@ $v.id}}" href="javascript:void(0);" class="comment-reply delete"><small>删除</small></a>
                            {{/if}}
                        </div>
                    </div>
                </li>
                {{/each}}
            </ul>
            {{/if}}
        </li>
        {{/each}}
    </ul>
    {{@ pagination}}
    {{else}}
        <div class="text-center">暂无评论，快来点评一下吧。</div>
    {{/if}}
</script>

<script id="input_tpl" type="text/html">
<div class="search-form reply-form">
    <input type="text" placeholder="回复当前用户..." class="search-input reply-input">
    <button type="submit" class="search-button btn btn-lg btn-color btn-button reply-btn">
        <i class="ui-success search-icon"></i>
    </button>
</div>
</script>

<!--script-->
<?php
$commitPath = Url::to(['/home/motion/comment/create']);
$fetchPath = Url::to(['/home/motion/comment/fetch']);
$replyPath = Url::to(['/home/motion/comment/reply']);
$deletePath = Url::to(['/home/motion/comment/delete']);
$js = <<<SCRIPT
    //提交评论操作
    var comment = {
        //请求地址
        commitPath : '',
        fetchPath : '',
        firstPagerPath : '',
        replyPath : '',
        
        //query对象
        input : '',
        btn : '',
        list : '',
        container : '',
        
        //当前文章id
        article_id : '',
    
    
        init : function(commitPath, fetchPath, replyPath, deletePath, container, article_id){
            //属性初始化
            comment.commitPath = commitPath;
            comment.fetchPath = fetchPath;
            comment.firstPagerPath = fetchPath;
            comment.replyPath = replyPath;
            comment.deletePath = deletePath;
            
            
            comment.container = $(container),
            comment.input = $(container).find('#comment_input');
            comment.btn = $(container).find('#comment_btn');
            comment.list = $(container).find('#comment_list');
            comment.article_id = article_id;
            
            //显示评论
            comment.fetch(false);
            
            //绑定事件
            comment.onCommit();
            
            //刷新分页
            comment.pager();
            
            //显示回复框
            comment.replyForm();
            
            //回复删除事件绑定
            comment.reply();
            comment.delete();
            
        },
        
        
        //渲染评论列表
        refresh : function(data, toTop){
            //渲染模板
            var html = template('comments_tpl',data);
            $('#comment_list').html(html);
            
            //回到评论顶端
            if(toTop)
                $(window).scrollTop(comment.list.offset().top - 60);
            
        },
        
        //回复提交
        reply : function(){
            comment.container.on('click', 'button.reply-btn', function(e){
                if($(this).hasClass('btn-disabled')) return;
                
                //禁止重复点击
                $(this).addClass('btn-disabled');
            
                var reply = $(this).closest('.reply-form').find('.reply-input');
                
                var parent_id = $(this).closest('.parent-comment').data('parent-id');
                var content = reply.val();
                
                if(content.length <= 0) return;
                
                
                //提交回复
                comment.commit(parent_id, content, reply, $(this));
                
                return false;
            });
        },
        
        //回复删除
        delete : function(){
            comment.container.on('click', 'a.delete', function(e){
                var id = $(this).data('id');
                
                layer.confirm('您确定要删除当前评论吗？', {
                  btn: ['是的','取消']
                }, function(){
                    $.ajax({
                        url : comment.deletePath,
                        type : 'POST',
                        data : {comment_id:id},
                        success : function(d){
                            if(d.errcode === 0){
                                //删除成功 刷新评论
                                comment.fetch();
                            }
                            layer.msg(d.message);
                        }
                    });
                    
                    layer.close(layer.index);
                    return false;
                });
                
                
            });
        },
        
        //回复框显示
        replyForm : function(){
            comment.container.on('click', 'a.reply', function(e){
                //先清空所有输入框
                comment.container.find('.reply-form').remove();
                
                $(this).before(template('input_tpl'));
                return false;
            });
        },
        
        //ajax分页
        pager : function(){
            $(document).on('click', '#pagination a', function(e){
                var href = $(this).attr('href');
                if(href.length <= 0) return;
                
                //显示loading...
                comment.list.html('<div class="text-center"><b>Loading...</b></div>');
                
                //请求数据
                comment.fetchPath = href;
                comment.fetch(true);
                
                
                e.preventDefault();
                return false;
           }); 
            
        },
        
        
        //获取评论列表
        fetch : function(toTop){
            $.ajax({
                url : comment.fetchPath,
                type : 'GET',
                data : {article_id:comment.article_id},
                success : function(d){
                    if(d.errcode === 0){
                        //获取成功 刷新评论列表
                        comment.refresh(d.data, toTop);
                        return;
                    }
                    console.log(d.message);
                    return;
                }
            });
        },
        
        
        
        
        //点击提交评论
        onCommit : function(){
            comment.btn.on('click', function(){
                if(comment.btn.hasClass('btn-disabled')) return;
                
                //禁止重复点击
                comment.btn.addClass('btn-disabled');
            
                var content = comment.input.val();
                
                //验证内容长度
                if(content.length <= 0) return;
                
                //提交评论
                comment.commit(0, content, comment.input, comment.btn);
                
            });
        },
        
        //提交评论
        commit : function(parent_id, content, oInput, oBtn){
            $.ajax({
                url  : comment.commitPath,
                type : 'POST',
                data : {article_id:comment.article_id, parent_id:parent_id, content:content},
                success : function(d){
                    if(d.errcode === 0){
                        //清空内容
                        oInput.val('');
                        
                        //提交成功刷新评论列表
                        comment.fetchPath = comment.firstPagerPath;
                        comment.fetch(true);
                    }else{
                        //失败提示
                        layer.msg(d.message);
                    }
                    
                    //可再次提交
                    oBtn.removeClass('btn-disabled');
                    return;
                }
            });
        }
        
    }
    
    //初始化
    comment.init("{$commitPath}", "{$fetchPath}", "{$replyPath}", "{$deletePath}", '#comment_wrap', "{$model['id']}");
SCRIPT;
$this->registerJs($js);

