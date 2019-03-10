<?php

use yii\db\Migration;

/**
 * Class m190310_021353_create_seo_tbl
 */
class m190310_021353_create_seo_tbl extends Migration
{
    const TBL_NAME = '{{%seo}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TBL_NAME,[
            'id' => $this->primaryKey()->unsigned()->comment('主键'),
            'name' => $this->string(7)->notNull()->defaultValue('')->comment('网站名称'),
            'keywords' => $this->string(125)->notNull()->defaultValue('')->comment('关键字'),
            'description' => $this->string(225)->notNull()->defaultValue('')->comment('描述'),
            'pc_logo' => $this->string(125)->notNull()->defaultValue('')->comment('PC端LOGO'),
            'mobile_logo' => $this->string(125)->notNull()->defaultValue('')->comment('移动端LOGO'),
            'qrcode' => $this->string(125)->notNull()->defaultValue('')->comment('二维码'),
            'about' => $this->text()->comment('关于我'),
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
        echo "m190310_021353_create_seo_tbl cannot be reverted.\n";

        return false;
    }
    */
}
