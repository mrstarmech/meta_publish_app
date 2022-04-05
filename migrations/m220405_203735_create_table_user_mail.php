<?php

use yii\db\Migration;

/**
 * Class m220405_203735_create_table_user_mail
 */
class m220405_203735_create_table_user_mail extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user_mail',[
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'email' => $this->string(),
            'time' => $this->time()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user_mail');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220405_203735_create_table_user_mail cannot be reverted.\n";

        return false;
    }
    */
}
