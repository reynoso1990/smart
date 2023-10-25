<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddAreaPrinterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('areas', function (Blueprint $table) {
            $table->string('printer')->nullable()->after('description');
            $table->string('copies',4)->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('areas', function(Blueprint $table) {
            $table->dropColumn('printer');
            $table->dropColumn('copies');
       });
    }
}
