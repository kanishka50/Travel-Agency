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
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('accepted_proposal_id')->nullable()->after('accepted_bid_id')
                ->constrained('plan_proposals')->onDelete('set null');
        });

        // Update booking_type enum to include 'plan_proposal'
        // Note: MySQL doesn't support easy enum modification, so we'll handle this in code
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['accepted_proposal_id']);
            $table->dropColumn('accepted_proposal_id');
        });
    }
};
