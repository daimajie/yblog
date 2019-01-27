<?php

use yii\db\Migration;

/**
 * Class m190122_144807_create_tbl_article
 */
class m190122_144807_create_tbl_article extends Migration
{
    const TBL_NAME = '{{%article}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TBL_NAME,[
            'id' => $this->primaryKey()->unsigned()->comment('主键'),
            'title' => $this->string(75)->notNull()->defaultValue('')->comment('文章标题'),
            'brief' => $this->string(225)->notNull()->defaultValue('')->comment('文章简介'),
            'image' => $this->string(125)->notNull()->defaultValue('')->comment('文章图片'),
            'status' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(1)->comment('文章状态: 1正常, 2草稿, 3回收站'),
            'check' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(1)->comment('审核状态: 1待审核, 2通过, 3审核失败'),
            'visited' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('访问次数'),
            'comment' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('评论数'),
            'topic_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('所属话题'),
            'user_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('作者'),
            'content' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('文章id'),
            'created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('创建时间'),
            'updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('修改时间'),

        ],'engine=innodb charset=utf8mb4');

        //创建索引
        $this->createIndex(
            'idx-user_id',
            self::TBL_NAME,
            'user_id'
        );

        $this->createIndex(
            'idx-created_at',
            self::TBL_NAME,
            'created_at'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TBL_NAME);
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190122_144807_create_tbl_article cannot be reverted.\n";

        return false;
    }
    */
}
