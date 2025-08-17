<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActivationTypeToOffersTable extends Migration
{
    public function up()
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->string('activation_type')->default('always')->after('is_active');
        });
    }

    public function down()
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->dropColumn('activation_type');
        });
    }
}