<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->decimal('total_amount', 10, 2);
            $table->decimal('platform_fee', 10, 2);
            $table->decimal('guide_payout', 10, 2);
            $table->boolean('guide_paid')->default(false);
            $table->timestamp('guide_paid_at')->nullable();
            $table->foreignId('paid_by_admin')->nullable()->constrained('admins')->onDelete('set null');
            $table->string('payment_method')->nullable();
            $table->text('payment_notes')->nullable();
            $table->timestamp('created_at')->useCurrent();
            
            $table->index('booking_id');
            $table->index('guide_paid');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_payments');
    }
};
