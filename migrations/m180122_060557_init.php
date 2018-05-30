<?php

use yii\db\Migration;

class m180122_060557_init extends Migration
{
    public function safeUp()
    {
        $tables = $this->db->schema->getTableNames();
        $dbType = $this->db->driverName;
        $tableOptions_mysql = "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        $tableOptions_mssql = "";
        $tableOptions_pgsql = "";
        $tableOptions_sqlite = "";

        /* MYSQL */
        if (!in_array('scheduler_status', $tables)) {
            if ($dbType == "mysql") {
                $this->createTable('{{%scheduler_status}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'name' => 'VARCHAR(45) NOT NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MSSQL */
        if (!in_array('scheduler_status', $tables)) {
            if ($dbType == "mssql") {
                $this->createTable('{{%scheduler_status}}', [
                    'id' => 'INT(11) IDENTITY NOT NULL',
                    0 => 'PRIMARY KEY (`id`)',
                    'name' => 'VARCHAR(45) NOT NULL',
                ], $tableOptions_mssql);
            }
        }

        /* PGSQL */
        if (!in_array('scheduler_status', $tables)) {
            if ($dbType == "pgsql") {
                $this->createTable('{{%scheduler_status}}', [
                    'id' => 'INT(11) SERIAL NOT NULL',
                    0 => 'PRIMARY KEY (`id`)',
                    'name' => 'VARCHAR(45) NOT NULL',
                ], $tableOptions_pgsql);
            }
        }

        /* SQLITE */
        if (!in_array('scheduler_status', $tables)) {
            if ($dbType == "sqlite") {
                $this->createTable('{{%scheduler_status}}', [
                    'id' => 'INT(11) NOT NULL AUTOINCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'name' => 'VARCHAR(45) NOT NULL',
                ], $tableOptions_sqlite);
            }
        }

        /* MYSQL */
        if (!in_array('scheduler_task', $tables)) {
            if ($dbType == "mysql") {
                $this->createTable('{{%scheduler_task}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'cron_time' => 'VARCHAR(65) NOT NULL',
                    'command' => 'VARCHAR(256) NOT NULL',
                    'comment' => 'VARCHAR(256) NULL',
                    'status_id' => 'INT(11) NOT NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MSSQL */
        if (!in_array('scheduler_task', $tables)) {
            if ($dbType == "mssql") {
                $this->createTable('{{%scheduler_task}}', [
                    'id' => 'INT(11) IDENTITY NOT NULL',
                    0 => 'PRIMARY KEY (`id`)',
                    'cron_time' => 'VARCHAR(65) NOT NULL',
                    'command' => 'VARCHAR(256) NOT NULL',
                    'comment' => 'VARCHAR(256) NULL',
                    'status_id' => 'INT(11) NOT NULL',
                ], $tableOptions_mssql);
            }
        }

        /* PGSQL */
        if (!in_array('scheduler_task', $tables)) {
            if ($dbType == "pgsql") {
                $this->createTable('{{%scheduler_task}}', [
                    'id' => 'INT(11) SERIAL NOT NULL',
                    0 => 'PRIMARY KEY (`id`)',
                    'cron_time' => 'VARCHAR(65) NOT NULL',
                    'command' => 'VARCHAR(256) NOT NULL',
                    'comment' => 'VARCHAR(256) NULL',
                    'status_id' => 'INT(11) NOT NULL',
                ], $tableOptions_pgsql);
            }
        }

        /* SQLITE */
        if (!in_array('scheduler_task', $tables)) {
            if ($dbType == "sqlite") {
                $this->createTable('{{%scheduler_task}}', [
                    'id' => 'INT(11) NOT NULL AUTOINCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'cron_time' => 'VARCHAR(65) NOT NULL',
                    'command' => 'VARCHAR(256) NOT NULL',
                    'comment' => 'VARCHAR(256) NULL',
                    'status_id' => 'INT(11) NOT NULL',
                ], $tableOptions_sqlite);
            }
        }

        /* MYSQL */
        if (!in_array('scheduler_task_log', $tables)) {
            if ($dbType == "mysql") {
                $this->createTable('{{%scheduler_task_log}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'start_date_time' => 'DATETIME NOT NULL',
                    'execution_time' => 'TIME NOT NULL',
                    'output' => 'TEXT NULL',
                    'task_id' => 'INT(11) NULL',
                    'status_id' => 'INT(11) NOT NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MSSQL */
        if (!in_array('scheduler_task_log', $tables)) {
            if ($dbType == "mssql") {
                $this->createTable('{{%scheduler_task_log}}', [
                    'id' => 'INT(11) IDENTITY NOT NULL',
                    0 => 'PRIMARY KEY (`id`)',
                    'start_date_time' => 'DATETIME NOT NULL',
                    'execution_time' => 'TIME NOT NULL',
                    'output' => 'TEXT NULL',
                    'task_id' => 'INT(11) NULL',
                    'status_id' => 'INT(11) NOT NULL',
                ], $tableOptions_mssql);
            }
        }

        /* PGSQL */
        if (!in_array('scheduler_task_log', $tables)) {
            if ($dbType == "pgsql") {
                $this->createTable('{{%scheduler_task_log}}', [
                    'id' => 'INT(11) SERIAL NOT NULL',
                    0 => 'PRIMARY KEY (`id`)',
                    'start_date_time' => 'DATETIME NOT NULL',
                    'execution_time' => 'TIME NOT NULL',
                    'output' => 'TEXT NULL',
                    'task_id' => 'INT(11) NULL',
                    'status_id' => 'INT(11) NOT NULL',
                ], $tableOptions_pgsql);
            }
        }

        /* SQLITE */
        if (!in_array('scheduler_task_log', $tables)) {
            if ($dbType == "sqlite") {
                $this->createTable('{{%scheduler_task_log}}', [
                    'id' => 'INT(11) NOT NULL AUTOINCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'start_date_time' => 'DATETIME NOT NULL',
                    'execution_time' => 'TIME NOT NULL',
                    'output' => 'TEXT NULL',
                    'task_id' => 'INT(11) NULL',
                    'status_id' => 'INT(11) NOT NULL',
                ], $tableOptions_sqlite);
            }
        }

        $this->createIndex('idx_status_id_3619_00', 'scheduler_task', 'status_id', 0);
        $this->createIndex('idx_status_id_3649_01', 'scheduler_task', 'status_id', 0);
        $this->createIndex('idx_status_id_3679_02', 'scheduler_task', 'status_id', 0);
        $this->createIndex('idx_status_id_3699_03', 'scheduler_task', 'status_id', 0);
        $this->createIndex('idx_task_id_3789_04', 'scheduler_task_log', 'task_id', 0);
        $this->createIndex('idx_status_id_3789_05', 'scheduler_task_log', 'status_id', 0);
        $this->createIndex('idx_task_id_3819_06', 'scheduler_task_log', 'task_id', 0);
        $this->createIndex('idx_status_id_3829_07', 'scheduler_task_log', 'status_id', 0);
        $this->createIndex('idx_task_id_3859_08', 'scheduler_task_log', 'task_id', 0);
        $this->createIndex('idx_status_id_3859_09', 'scheduler_task_log', 'status_id', 0);
        $this->createIndex('idx_task_id_3879_10', 'scheduler_task_log', 'task_id', 0);
        $this->createIndex('idx_status_id_3879_11', 'scheduler_task_log', 'status_id', 0);

        $this->execute('SET foreign_key_checks = 0');
        $this->addForeignKey('fk_scheduler_status_3609_00', '{{%scheduler_task}}', 'status_id', '{{%scheduler_status}}',
            'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_scheduler_status_3639_01', '{{%scheduler_task}}', 'status_id', '{{%scheduler_status}}',
            'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_scheduler_status_3669_02', '{{%scheduler_task}}', 'status_id', '{{%scheduler_status}}',
            'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_scheduler_status_3789_03', '{{%scheduler_task_log}}', 'status_id',
            '{{%scheduler_status}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_scheduler_task_3789_04', '{{%scheduler_task_log}}', 'task_id', '{{%scheduler_task}}',
            'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_scheduler_status_3809_05', '{{%scheduler_task_log}}', 'status_id',
            '{{%scheduler_status}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_scheduler_task_3809_06', '{{%scheduler_task_log}}', 'task_id', '{{%scheduler_task}}',
            'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_scheduler_status_3839_07', '{{%scheduler_task_log}}', 'status_id',
            '{{%scheduler_status}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_scheduler_task_3839_08', '{{%scheduler_task_log}}', 'task_id', '{{%scheduler_task}}',
            'id', 'CASCADE', 'CASCADE');
        $this->execute('SET foreign_key_checks = 1;');

        $this->execute('SET foreign_key_checks = 0');
        $this->insert('{{%scheduler_status}}', ['id' => '1', 'name' => 'On']);
        $this->insert('{{%scheduler_status}}', ['id' => '2', 'name' => 'Off']);
        $this->insert('{{%scheduler_status}}', ['id' => '3', 'name' => 'Success']);
        $this->insert('{{%scheduler_status}}', ['id' => '4', 'name' => 'Error']);
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function safeDown()
    {
        $this->execute('SET foreign_key_checks = 0');
        $this->execute('DROP TABLE IF EXISTS `scheduler_status`');
        $this->execute('SET foreign_key_checks = 1;');
        $this->execute('SET foreign_key_checks = 0');
        $this->execute('DROP TABLE IF EXISTS `scheduler_task`');
        $this->execute('SET foreign_key_checks = 1;');
        $this->execute('SET foreign_key_checks = 0');
        $this->execute('DROP TABLE IF EXISTS `scheduler_task_log`');
        $this->execute('SET foreign_key_checks = 1;');
    }
}
