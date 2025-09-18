<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->json('pending_updates')->nullable()->after('activation_type');
            $table->timestamp('scheduled_publish_at')->nullable()->after('pending_updates');
            $table->boolean('has_pending_updates')->default(false)->after('scheduled_publish_at');
        });
    }

    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->dropColumn(['pending_updates', 'scheduled_publish_at', 'has_pending_updates']);
        });
    }
};