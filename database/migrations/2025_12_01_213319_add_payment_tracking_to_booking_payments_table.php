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
        Schema::table('booking_payments', function (Blueprint $table) {
            // Add new columns for tracking partial payments
            $table->decimal('amount_paid', 10, 2)->default(0)->after('guide_payout');
            $table->decimal('amount_remaining', 10, 2)->default(0)->after('amount_paid');
        });

        // Backfill existing records
        // For unpaid bookings: amount_remaining = guide_payout
        // For paid bookings: amount_paid = guide_payout, amount_remaining = 0
        DB::statement('UPDATE booking_payments SET amount_remaining = guide_payout WHERE guide_paid = false');
        DB::statement('UPDATE booking_payments SET amount_paid = guide_payout, amount_remaining = 0 WHERE guide_paid = true');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_payments', function (Blueprint $table) {
            $table->dropColumn(['amount_paid', 'amount_remaining']);
        });
    }
};
