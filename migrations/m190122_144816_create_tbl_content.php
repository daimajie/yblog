<?php

use yii\db\Migration;

/**
 * Class m190122_144816_create_tbl_content
 */
class m190122_144816_create_tbl_content extends Migration
{
    const TBL_NAME = '{{%content}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TBL_NAME, [
            'id' => $this->primaryKey()->unsigned()->comment('主键'),
            'words' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('文章字数'),
            'content' => $this->text()->comment('文章内容'),

        ], 'engine=innodb charset=utf8mb4');

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
        echo "m190122_144816_create_tbl_content cannot be reverted.\n";

        return false;
    }
    */
}
