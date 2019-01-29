<?php
/**
 * Created by PhpStorm.
 * User : daimajie
 * Email: daimajie@qq.com
 * Date : 2019/1/29
 * Time : 15:26
 */
use app\modules\admin\models\Article;

//定义下拉菜单
$li = '';
switch ($status){
    case Article::STATUS_RECYCLE: //回收站(批量恢复)
        $li .= '<li><a data-operate="batchRestore" href="javascript:void(0);">批量恢复</a></li>';
        break;
    case Article::STATUS_DRAFT: //草稿箱(批量删除 批量发布)
        $li .= '<li><a data-operate="batchDelete" href="javascript:void(0);">批量删除</a></li>';
        $li .= '<li><a data-operate="batchPublish" href="javascript:void(0);">批量发布</a></li>';
        break;
    case Article::STATUS_NORMAL: //列表页(批量删除)
        $li .= '<li><a data-operate="batchDelete" href="javascript:void(0);">批量删除</a></li>';
        break;
}
//如果展示的是未审核 展示批量审核通过
if(isset($searchModel->check) && (int)$searchModel->check === Article::CHECK_WAIT){
    $li .= '<li><a data-operate="batchCheck" href="javascript:void(0);">批量审核</a></li>';
}

$batchBtn = <<<"DROP"
    <div class="btn-group dropup">
      <button type="button" class="btn btn-info btn-flat">批量操作</button>
      <button type="button" class="btn btn-info btn-flat dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
      </button>
      <ul class="dropdown-menu" id="operate_art">
        {$li}
      </ul>
    </div>
DROP;

return $batchBtn;