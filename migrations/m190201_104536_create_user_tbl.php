<?php

use yii\db\Migration;

/**
 * Class m190201_104536_create_user_tbl
 */
class m190201_104536_create_user_tbl extends Migration
{
    const TBL_NAME = '{{%user}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TBL_NAME,[
            'id'        => $this->primaryKey()->unsigned()->comment('主键'),
            'username'  => $this->string(18)->notNull()->unique()->defaultValue('')->comment('用户名'),
            'nickname'  => $this->string(18)->notNull()->defaultValue('')->comment('昵称'),
            'email'     => $this->string(64)->notNull()->unique()->defaultValue('')->comment('邮箱'),
            'image'     => $this->string(64)->notNull()->defaultValue('')->comment('头像'),
            'status'    => $this->tinyInteger()->unsigned()->notNull()->defaultValue(10)->comment('状态'),
            'author'    => $this->integer()->notNull()->defaultValue(-1)->comment('文章数目：-1非作者'),
            'auth_key'  => $this->string()->notNull()->defaultValue('')->comment('Auth_Key'),
            'password_hash' => $this->string()->notNull()->defaultValue('')->comment('密码'),
            'password_reset_token' => $this->string()->notNull()->defaultValue('')->comment('密码重置key'),
            'created_at'=> $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('注册时间'),
            'updated_at'=> $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('修改时间'),
        ],'engine=innodb charset=utf8mb4');


        //账号和密码复合索引
        $this->createIndex(
            'idx-user-username-password',
            self::TBL_NAME,
            ['username', 'password_hash']
        );

        //邮箱和密码复合索引
        $this->createIndex(
            'idx-user-email-password',
            self::TBL_NAME,
            ['email', 'password_hash']
        );

        //文章数目一般索引用于排序
        $this->createIndex(
            'idx-user-author',
            self::TBL_NAME,
            'author'
        );

        //auth_key 普通索引
        $this->createIndex(
            'idx-user-auth_key',
            self::TBL_NAME,
            'auth_key'
        );

        //password_reset_token 普通索引
        $this->createIndex(
            'idx-user-password_reset_token',
            self::TBL_NAME,
            'password_reset_token'
        );

        //创建时间普通索引 用于排序
        $this->createIndex(
            'idx-user-created_at',
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

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190201_104536_create_user_tbl cannot be reverted.\n";

        return false;
    }
    */
}
