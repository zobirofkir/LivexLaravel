<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->dateTime('valid_until')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->date('valid_until')->nullable()->change();
        });
    }
};