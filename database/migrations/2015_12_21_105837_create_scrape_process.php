<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScrapeProcess extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scrape_process', function (Blueprint $table) {
            $table->increments('id');
						$table->string('date_start');
						$table->string('date_end');
						$table->string('status');
						$table->string('status_name');
						$table->string('process_id');
						$table->longText('misc_info');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('scrape_process');
    }
}
