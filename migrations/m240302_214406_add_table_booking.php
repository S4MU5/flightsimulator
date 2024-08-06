<?php

use yii\db\Migration;
use app\models\User;
use app\models\Bookings;
use app\models\Borts;

/**
 * Class m240302_214406_add_table_booking
 */
class m240302_214406_add_table_booking extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(Bookings::tableName(), [
            'id' => $this->primaryKey(),
            'bort_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'status' => $this->tinyInteger()->notNull(),
            'start_date' => $this->dateTime()->notNull(),
            'end_date' => $this->dateTime()->notNull(),
            'comments' => $this->string()->null(),
            'purpose' => $this->string()->notNull()
        ]);

        $this->addForeignKey(
            'fk-booking-bort_id',
            Bookings::tableName(),
            'bort_id',
            Borts::tableName(),
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-booking-user_id',
            Bookings::tableName(),
            'user_id',
            User::tableName(),
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-booking-bort_id', 'bookings');
        $this->dropForeignKey('fk-booking-user_id', 'bookings');

        $this->dropTable(Bookings::tableName());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240302_214406_add_table_booking cannot be reverted.\n";

        return false;
    }
    */
}
