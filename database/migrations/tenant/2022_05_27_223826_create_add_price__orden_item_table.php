<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddPriceOrdenItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orden_item', function (Blueprint $table) {
            $table->decimal('price')->nullable()->default(0)->after('quantity');
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::table('orden_item', function(Blueprint $table) {
             $table->dropColumn('price');
       });
    }
}
