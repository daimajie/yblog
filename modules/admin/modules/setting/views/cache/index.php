<?php
use yii\helpers\Html;
$this->title = '缓存操作';
?>

<div class="user-form-index box box-primary">
    <div class="box-header with-border">
        <div class="pull-left">
            <?= Html::a('清空所有缓存', ['flush'], [
                'class' => 'btn btn-success btn-flat',
                'onclick' => 'javascript:return confirm("您确定删除所有缓存数据吗？");'
            ]) ?>
        </div>
        <div class="pull-right">
        </div>
    </div>
    <div class="box-body table-responsive no-padding">
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <tbody><tr>
                    <th>ID</th>
                    <th>缓存描述</th>
                    <th>缓存状态</th>
                    <th>操作</th>
                </tr>
                <?php
                foreach( $caches as $k => $cache ):
                    ?>

                    <tr>
                        <td><?= strtoupper($k)?></td>
                        <td><?= $cache['desc']?></td>
                        <td><?= $cache['exists'] ? '存在' : '不存在'?></td>
                        <td><?= Html::a('删除', [$k], [
                                'class' => 'btn btn-success btn-flat',
                                'onclick' => 'javascript:return confirm("您确定删除该缓存数据吗？");'
                            ]) ?></td>
                    </tr>
                <?php
                endforeach;
                ?>

                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
</div>