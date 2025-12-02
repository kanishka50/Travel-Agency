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
        // For MySQL, we need to alter the ENUM column to add 'upcoming' status
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending_payment', 'payment_failed', 'confirmed', 'upcoming', 'ongoing', 'completed', 'cancelled_by_tourist', 'cancelled_by_guide', 'cancelled_by_admin') DEFAULT 'pending_payment'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'upcoming' status from ENUM
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending_payment', 'payment_failed', 'confirmed', 'ongoing', 'completed', 'cancelled_by_tourist', 'cancelled_by_guide', 'cancelled_by_admin') DEFAULT 'pending_payment'");
    }
};
