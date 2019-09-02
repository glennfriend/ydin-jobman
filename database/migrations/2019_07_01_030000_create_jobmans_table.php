<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobmansTable extends Migration
{
    protected $table = 'jobmans';

    public function up()
    {
        Schema::table(null, function(Blueprint $table){
            $sql =<<<EOD

CREATE TABLE `{$this->table}` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(64) NOT NULL,
  `status` varchar(8) NULL DEFAULT NULL COMMENT 'complete, fail',
  `logs` text NULL DEFAULT NULL,
  `error_message` text NULL DEFAULT NULL,
  `exception` text NULL DEFAULT NULL,
  `attribs` text NOT NULL,
  `completed_code` varchar(64) NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `failed_at` timestamp NULL DEFAULT NULL,
  `executed_total` smallint(10) UNSIGNED NOT NULL DEFAULT 0,
  `executed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `{$this->table}`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`),
  ADD KEY `status` (`status`);

ALTER TABLE `{$this->table}`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

EOD;

            DB::connection()->getPdo()->exec($sql);
        });
    }

    public function down()
    {
        Schema::drop($this->table);
    }

}
