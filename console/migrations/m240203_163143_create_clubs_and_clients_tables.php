<?php

use yii\db\Migration;

/**
 * Class m240203_163143_create_clubs_and_clients_tables
 */
class m240203_163143_create_clubs_and_clients_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%clients}}', [
            'id' => $this->primaryKey(),
            'fio' => $this->string()->notNull(),
            'sex' => $this->string(),
            'birthday' => $this->date(),
            'created_at' => $this->integer()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer(),
        ], $tableOptions);

        $this->createTable('{{%clubs}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'address' => $this->string()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer(),
        ], $tableOptions);

        $this->createTable('{{%client_to_clubs}}', [
            'id' => $this->primaryKey(),
            'client_id' => $this->integer()->notNull(),
            'club_id' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('{{%clients}}');
        $this->dropTable('{{%clubs}}');
        $this->dropTable('{{%client_to_clubs}}');
    }
}
