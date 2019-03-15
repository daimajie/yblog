<?php

use yii\db\Migration;

/**
 * Class m190315_142446_create_advert_tbl
 */
class m190315_142446_create_advert_tbl extends Migration
{
    const TBL_NAME = '{{%advert}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TBL_NAME,[
            'id' =>$this->primaryKey()->unsigned()->comment('主键'),
            'switch' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(1)->comment('广告开关'),
            'advert_bar' => $this->text()->comment('页头广告'),
            'advert' => $this->text()->comment('侧边栏广告'),
            'updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('修改时间')
        ],'engine=innodb charset=utf8mb4');
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
        echo "m190315_142446_create_advert_tbl cannot be reverted.\n";

        return false;
    }
    */
}
