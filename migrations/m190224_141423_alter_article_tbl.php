<?php

use yii\db\Migration;

/**
 * Class m190224_141423_alter_article_tbl
 */
class m190224_141423_alter_article_tbl extends Migration
{
    const TBL_NAME = '{{%article}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            self::TBL_NAME,
            'category_id',
            $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('文章分类')->after('topic_id')
        );

        $this->createIndex(
            'idx-category_id',
            self::TBL_NAME,
            'category_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(self::TBL_NAME,'category_id');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190224_141423_alter_article_tbl cannot be reverted.\n";

        return false;
    }
    */
}
