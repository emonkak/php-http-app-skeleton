<?php

use Phpmig\Migration\Migration;

class CreateAccountsTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $this->get('db')->exec(<<<SQL
CREATE TABLE `accounts` (
  `account_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email_address` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
SQL
        );
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $this->get('db')->exec(<<<SQL
DROP TABLE `accounts`;
SQL
        );
    }
}
