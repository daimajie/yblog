<?php

use yii\db\Migration;

/**
 * Class m190120_090630_create_tbl_topic
 */
class m190120_090630_create_tbl_topic extends Migration
{
    const TBL_NAME = '{{%topic}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TBL_NAME,[
            'id' => $this->primaryKey()->unsigned()->comment('主键'),
            'name' => $this->string(18)->notNull()->defaultValue('')->comment('话题名称'),
            'image' => $this->string(125)->notNull()->defaultValue('')->comment('话题封面'),
            'desc' => $this->string(225)->notNull()->defaultValue('')->comment('话题描述'),
            'count' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('收录文章'),
            'status' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(1)->comment('话题状态: 1 正常,2 完结,3 删除'),
            'check' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(1)->comment('审核状态: 1 待审核,2 审核通过,3 审核失败'),
            'secrecy' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(1)->comment('私有话题: 1 私有,2 公开'),
            'user_id' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('创建者'),
            'created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('创建时间'),
            'updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('修改时间')
        ], 'engine=innodb charset=utf8mb4');

        //创建索引
        $this->createIndex(
            'idx-user_id',
            self::TBL_NAME,
            'user_id'
        );

        $this->createIndex(
            'idx-created_at',
            self::TBL_NAME,
            'created_at'
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
}
