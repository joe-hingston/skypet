<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndextoOutputs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('outputs', function(Blueprint $table)
        {
            $table->index('id');
            $table->unique('doi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('outputs', function (Blueprint $table)
        {
            $table->dropIndex(['id']);
            $table->dropUnique(['doi']);
        });
    }
}
