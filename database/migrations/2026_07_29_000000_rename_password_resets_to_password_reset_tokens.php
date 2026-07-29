<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Laravel 8 renamed the password broker's table to `password_reset_tokens`,
 * which is what config/auth.php now points at. Existing deployments still hold
 * the 5.8-era `password_resets` table, so rename it in place instead of
 * rewriting the original migration and breaking migration history.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('password_resets') && ! Schema::hasTable('password_reset_tokens')) {
            Schema::rename('password_resets', 'password_reset_tokens');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('password_reset_tokens') && ! Schema::hasTable('password_resets')) {
            Schema::rename('password_reset_tokens', 'password_resets');
        }
    }
};
