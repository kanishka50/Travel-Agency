<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number', 50)->unique();
            $table->enum('booking_type', ['guide_plan', 'custom_request']);
            $table->foreignId('tourist_id')->constrained('tourists')->onDelete('cascade');
            $table->foreignId('guide_id')->constrained('guides')->onDelete('cascade');
            $table->foreignId('guide_plan_id')->nullable()->constrained('guide_plans')->onDelete('set null');
            $table->foreignId('tourist_request_id')->nullable()->constrained('tourist_requests')->onDelete('set null');
            $table->foreignId('accepted_bid_id')->nullable()->constrained('bids')->onDelete('set null');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('num_adults');
            $table->integer('num_children')->default(0);
            $table->json('children_ages')->nullable();
            $table->decimal('base_price', 10, 2);
            $table->decimal('addons_total', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('platform_fee', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('guide_payout', 10, 2);
            $table->enum('status', ['pending_payment', 'payment_failed', 'confirmed', 'ongoing', 'completed', 'cancelled_by_tourist', 'cancelled_by_guide', 'cancelled_by_admin'])->default('pending_payment');
            $table->string('payment_intent_id')->nullable();
            $table->string('payment_method')->nullable();
            $table->timestamp('payment_completed_at')->nullable();
            $table->text('tourist_notes')->nullable();
            $table->text('guide_notes')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
            
            $table->index('booking_number');
            $table->index('tourist_id');
            $table->index('guide_id');
            $table->index('status');
            $table->index('start_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
