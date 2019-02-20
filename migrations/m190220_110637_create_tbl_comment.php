<?php

use yii\db\Migration;

/**
 * Class m190220_110637_create_tbl_comment
 */
class m190220_110637_create_tbl_comment extends Migration
{
    const TBL_NAME = '{{%comment}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TBL_NAME,[
            'id' => $this->primaryKey()->unsigned()->comment('主键'),
            'content' => $this->string(512)->notNull()->defaultValue('')->comment('评论内容'),
            'parent_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('回复'),
            'user_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('用户'),
            'created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('评论时间'),
        ],'engine=innodb charset=utf8mb4');

        $this->createIndex(
            'idx-parent_id',
            self::TBL_NAME,
            'parent_id'
        );

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
        echo "m190220_110637_create_tbl_comment cannot be reverted.\n";

        return false;
    }
    */
}
