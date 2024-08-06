<?php

use yii\db\Migration;

/**
 * Class m240607_112827_add_column_to_issues_active_set
 */
class m240607_112827_add_column_to_issues_active_set extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(\app\models\Issues::tableName(), 'active', $this->boolean()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(\app\models\Issues::tableName(), 'active');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240607_112827_add_column_to_issues_active_set cannot be reverted.\n";

        return false;
    }
    */
}
