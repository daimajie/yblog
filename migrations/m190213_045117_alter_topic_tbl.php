<?php

use yii\db\Migration;

/**
 * Class m190213_045117_alter_topic_tbl
 */
class m190213_045117_alter_topic_tbl extends Migration
{
    const TBL_NAME = '{{%topic}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(self::TBL_NAME,
            'category_id',
            $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('所属分类')->after('user_id')
        );

        //添加索引
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
        $this->dropIndex(
            'idx-category_id',
            self::TBL_NAME
        );

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190213_045117_alter_topic_tbl cannot be reverted.\n";

        return false;
    }
    */
}
