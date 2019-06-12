<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHealthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('healths', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->datetime('journal_updater_ran')->nullable();
            $table->datetime('output_updater_ran')->nullable();
            $table->integer('new_outputs')->nullable();
            $table->integer('total_articles')->nullable();
            $table->integer('missing_abstracts')->nullable();
            $table->datetime('backup_ran')->nullable();
            $table->string('backup_string')->nullable();
            $table->unsignedInteger('journal_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('healths');
    }
}
