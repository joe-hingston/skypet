<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->text('title')->nullable();
            $table->string('doi')->nullable();
            $table->dateTimeTz('created')->nullable();
            $table->dateTimeTz('desposited')->nullable();
            $table->string('publisher')->nullable();
            $table->string('issn')->nullable();
            $table->string('eissn')->nullable();
            $table->string('language')->nullable();
            $table->string('license')->nullable();
            $table->integer('reference_count')->nullable();
            $table->string('page')->nullable();
            $table->string('url')->nullable();
            $table->string('miningurl')->nullable();
            $table->binary('abstract')->nullable();
            $table->integer('is_referenced_by')->nullable();
            $table->foreign('journal_id')->references('id')->on('journals')->onDelete('cascade');
            $table->string('source')->nullable();
            $table->string('prefix')->nullable();
            $table->string('volume')->nullable();
            $table->string('member')->nullable();
            $table->string('score')->nullable();
            $table->string('issue')->nullable();
            $table->string('short-container-title')->nullable();
            $table->string('container-title')->nullable();

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
