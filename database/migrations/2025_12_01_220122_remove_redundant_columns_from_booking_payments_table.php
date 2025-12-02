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
            // Drop foreign key constraint first
            $table->dropForeign(['paid_by_admin']);

            // Remove redundant columns - all this info is now in guide_payment_transactions table
            $table->dropColumn([
                'guide_paid',
                'guide_paid_at',
                'paid_by_admin',
                'payment_method',
                'payment_notes'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_payments', function (Blueprint $table) {
            // Restore columns if rollback is needed
            $table->boolean('guide_paid')->default(false)->after('amount_remaining');
            $table->timestamp('guide_paid_at')->nullable()->after('guide_paid');
            $table->foreignId('paid_by_admin')->nullable()->constrained('admins')->onDelete('set null')->after('guide_paid_at');
            $table->string('payment_method')->nullable()->after('paid_by_admin');
            $table->text('payment_notes')->nullable()->after('payment_method');
        });
    }
};
