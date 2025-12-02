<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // MySQL requires this approach to modify ENUM columns
        DB::statement("ALTER TABLE bookings MODIFY COLUMN booking_type ENUM('guide_plan', 'custom_request', 'plan_proposal') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original ENUM values (note: this will fail if any rows have 'plan_proposal')
        DB::statement("ALTER TABLE bookings MODIFY COLUMN booking_type ENUM('guide_plan', 'custom_request') NOT NULL");
    }
};
