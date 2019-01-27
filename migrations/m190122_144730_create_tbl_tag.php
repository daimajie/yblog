<?php

use yii\db\Migration;

/**
 * Class m190122_144730_create_tbl_tag
 */
class m190122_144730_create_tbl_tag extends Migration
{
    const TBL_NAME = '{{%tag}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TBL_NAME,[
            'id' => $this->primaryKey()->unsigned()->comment('主键'),
            'name' => $this->string(8)->notNull()->defaultValue('')->comment('标签名称'),
            'topic_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('所属话题'),
            'user_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('创建者'),
            'created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('创建时间'),
            'updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('修改时间'),
        ],'engine=innodb charset=utf8mb4');

        //创建索引
        $this->createIndex(
            'idx-topic_id',
            self::TBL_NAME,
            'topic_id'
        );

        $this->createIndex(
            'idx-user_id',
            self::TBL_NAME,
            'user_id'
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
        echo "m190122_144730_create_tbl_tag cannot be reverted.\n";

        return false;
    }
    */
}
