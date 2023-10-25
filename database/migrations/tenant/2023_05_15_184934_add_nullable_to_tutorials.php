<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNullableToTutorials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tutorials', function (Blueprint $table) {
            $table->string('title')->unsigned()->nullable()->change();
            $table->string('description')->unsigned()->nullable()->change();
            $table->string('link')->unsigned()->nullable()->change();
            $table->boolean('type')->default(false)->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tutorials', function (Blueprint $table) {
            //
        });
    }
}
