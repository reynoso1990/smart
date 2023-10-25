<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddQuantityOrdenItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orden_item', function (Blueprint $table) {
            $table->integer('quantity')->default(0)->nullable()->after('food_id');
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orden_item', function (Blueprint $table) {
            $table->dropColumn('quantity');
       });
    }
}
