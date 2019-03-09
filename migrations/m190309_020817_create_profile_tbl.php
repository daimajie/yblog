<?php

use yii\db\Migration;

/**
 * Class m190309_020817_create_profile_tbl
 */
class m190309_020817_create_profile_tbl extends Migration
{
    const TBL_NAME = '{{%profile}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TBL_NAME,[
            'id' => $this->primaryKey()->unsigned()->comment('主键'),
            'address' => $this->string(64)->notNull()->defaultValue('')->comment('地址'),
            'intro' => $this->string(125)->notNull()->defaultValue('')->comment('介绍'),
            'blog' => $this->string(64)->notNull()->defaultValue('')->comment('博客地址'),
            'photo' => $this->string(125)->notNull()->defaultValue('')->comment('生活照'),
            'qrcode' => $this->string(125)->notNull()->defaultValue('')->comment('打赏二维码'),
            'contact' => $this->string(512)->notNull()->defaultValue('')->comment('社交账号'),
            'user_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('所属用户'),
        ],'engine=innodb charset=utf8mb4');

        $this->addForeignKey(
            'fk-user-user_id',
            self::TBL_NAME,
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
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
        echo "m190309_020817_create_profile_tbl cannot be reverted.\n";

        return false;
    }
    */
}
