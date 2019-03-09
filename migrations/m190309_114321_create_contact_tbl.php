<?php

use yii\db\Migration;

/**
 * Class m190309_114321_create_contact_tbl
 */
class m190309_114321_create_contact_tbl extends Migration
{
    const TBL_NAME = '{{%contact}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TBL_NAME,[
            'id' => $this->primaryKey()->unsigned()->comment('主键'),
            'email' => $this->string(64)->notNull()->defaultValue('')->comment('联系邮箱'),
            'subject' => $this->string(125)->notNull()->defaultValue('')->comment('主题'),
            'message' => $this->string(255)->notNull()->defaultValue('')->comment('消息主体'),
            'user_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('用户'),
            'created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('创建时间'),
        ],'engine=innodb charset=utf8mb4');


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
        echo "m190309_114321_create_contact_tbl cannot be reverted.\n";

        return false;
    }
    */
}
