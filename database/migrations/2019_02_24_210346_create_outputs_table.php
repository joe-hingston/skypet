<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOutputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outputs', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('journal_id');


            $table->string('title')->nullable();
            $table->string('doi')->nullable();
            $table->string('publisher')->nullable();
            $table->string('issn')->nullable();
            $table->string('eissn')->nullable();
            $table->string('language')->nullable();
            $table->string('license')->nullable();

            $table->foreign('journal_id')->references('id')->on('journals')->onDelete('cascade');

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
        Schema::dropIfExists('outputs');
    }
}
