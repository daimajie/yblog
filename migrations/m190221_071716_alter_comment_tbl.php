<?php

use yii\db\Migration;

/**
 * Class m190221_071716_alter_comment_tbl
 */
class m190221_071716_alter_comment_tbl extends Migration
{
    const TBL_NAME = '{{%comment}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            self::TBL_NAME,
            'article_id',
            $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('评论文章')->after('content')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(self::TBL_NAME,'article_id');
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190221_071716_alter_comment_tbl cannot be reverted.\n";

        return false;
    }
    */
}
