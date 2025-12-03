<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, we need to change the enum to allow the new values
        DB::statement("ALTER TABLE reports MODIFY COLUMN type ENUM('attendance', 'subscription', 'revenue', 'fitness_offers', 'sales', 'overall_sales')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE reports MODIFY COLUMN type ENUM('attendance', 'subscription', 'revenue')");
    }
};