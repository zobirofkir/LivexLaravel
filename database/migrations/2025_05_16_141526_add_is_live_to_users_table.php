<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /**
         * Ensure the column doesn't already exist before adding it
         */
        if (!Schema::hasColumn('users', 'is_live')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_live')->default(false)->after('profile_image');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /**
         * Ensure the column exists before trying to drop it
         */
        if (Schema::hasColumn('users', 'is_live')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_live');
            });
        }
    }
};
