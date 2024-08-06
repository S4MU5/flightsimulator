<?php

use yii\db\Migration;
use app\models\User;

/**
 * Class m240321_104606_add_column_bantime_to_table_users
 */
class m240321_104606_add_column_bantime_to_table_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(User::tableName(), 'ban_time', $this->dateTime()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(User::tableName(), 'ban_time');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240321_104606_add_column_bantime_to_table_users cannot be reverted.\n";

        return false;
    }
    */
}
