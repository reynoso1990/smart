<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddTotalIgvFreeToSaleNotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_notes', function (Blueprint $table) {
            $table->decimal('total_igv_free', 12, 2)->default(0)->after('total_igv');
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
            $table->dropColumn('total_igv_free');
        });
    }
}
