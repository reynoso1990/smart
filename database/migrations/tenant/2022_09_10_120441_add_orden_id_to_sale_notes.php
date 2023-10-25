<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrdenIdToSaleNotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_notes', function (Blueprint $table) {
            $table->unsignedInteger('orden_id')->nullable();
            $table->foreign('orden_id')->references('id')->on('ordens');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_notes', function (Blueprint $table) {
            //
        });
    }
}
