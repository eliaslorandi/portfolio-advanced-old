<?php

use yii\db\Migration;

/**
 * Class m231122_132101_alter_date_columns_in_project_table
 */
class m231122_132101_alter_date_columns_in_project_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn(table: 'project', column: 'start_date', type: 'date');
        $this->alterColumn(table: 'project', column: 'end_date', type: 'date');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn(table: 'project', column: 'start_date', type: 'integer');
        $this->alterColumn(table: 'project', column: 'end_date', type: 'integer');
    }

}
