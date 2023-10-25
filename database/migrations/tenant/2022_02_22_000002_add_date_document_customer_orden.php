<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDateDocumentCustomerOrden extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ordens', function (Blueprint $table) {
            $table->date('date')->nullable();
            //*
            $table->unsignedInteger('customer_id')->nullable()->after('status_orden_id');
            $table->foreign('customer_id')->references('id')->on('persons');
            //*
            $table->unsignedInteger('document_id')->nullable()->after('status_orden_id');
            $table->foreign('document_id')->references('id')->on('documents');
            //*
            $table->unsignedInteger('sale_note_id')->nullable()->after('status_orden_id');
            $table->foreign('sale_note_id')->references('id')->on('sale_notes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ordens', function (Blueprint $table) {
            $table->dropColumn('date');
            $table->dropColumn('customer_id');
            $table->dropColumn('document_id');
            $table->dropColumn('sale_note_id');
        });
    }
}
