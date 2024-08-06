<?php

use yii\db\Migration;
use app\models\Borts;
use app\models\Exploitation;

/**
 * Class m240608_161226_create_table_usage_borts
 */
class m240608_161226_create_table_usage_borts extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(Exploitation::tableName(), [
            'id' => $this->primaryKey(),
            'bort_id' => $this->integer()->notNull(),
            'status' => $this->boolean()->notNull(),
            'date' => $this->dateTime()->notNull(),
            'comment' => $this->string()
        ]);

        $this->addForeignKey(
            'fk-exploit-bort_id',
            Exploitation::tableName(),
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
        $this->dropForeignKey('fk-exploit-bort_id', Exploitation::tableName());

        $this->dropTable(Exploitation::tableName());
    }
}
