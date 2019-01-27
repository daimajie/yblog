<?php

use yii\db\Migration;

/**
 * Class m190125_070501_alter_tbl_article
 */
class m190125_070501_alter_tbl_article extends Migration
{
    const TBL_NAME = '{{%article}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn(self::TBL_NAME,'content','content_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn(self::TBL_NAME,'content_id', 'content');
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190125_070501_alter_tbl_article cannot be reverted.\n";

        return false;
    }
    */
}
