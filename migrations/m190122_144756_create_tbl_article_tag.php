<?php

use yii\db\Migration;

/**
 * Class m190122_144756_create_tbl_article_tag
 */
class m190122_144756_create_tbl_article_tag extends Migration
{
    const TBL_NAME = '{{%article_tag}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TBL_NAME,[
            'article_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('文章id'),
            'tag_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('标签id'),
        ],'engine=innodb charset=utf8mb4');

        //创建索引
        $this->createIndex(
            'idx-article_id',
            self::TBL_NAME,
            'article_id'
        );

        $this->createIndex(
            'idx-tag_id',
            self::TBL_NAME,
            'tag_id'
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
        echo "m190122_144756_create_tbl_article_tag cannot be reverted.\n";

        return false;
    }
    */
}
