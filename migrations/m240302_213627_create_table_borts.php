<?php

use yii\db\Migration;
use app\models\Borts;
use app\models\Issues;

/**
 * Class m240302_213627_create_table_borts
 */
class m240302_213627_create_table_borts extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(Borts::tableName(), [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'number' => $this->string()->notNull()
        ]);

        $this->createTable(Issues::tableName(), [
            'id' => $this->primaryKey(),
            'bort_id' => $this->integer()->notNull(),
            'issue' => $this->string()->notNull(),
            'answer' => $this->string()->notNull()
        ]);

        $this->addForeignKey(
            'fk-issues-bort_id',
            Issues::tableName(),
            'bort_id',
            Borts::tableName(),
            'id',
            'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-issues-bort_id', Issues::tableName());
        $this->dropTable(Issues::tableName());
        $this->dropTable(Borts::tableName());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240302_213627_create_table_borts cannot be reverted.\n";

        return false;
    }
    */
}
