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
        Schema::create('plan_proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guide_plan_id')->constrained('guide_plans')->onDelete('cascade');
            $table->foreignId('tourist_id')->constrained('tourists')->onDelete('cascade');
            $table->decimal('proposed_price', 10, 2);
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedInteger('num_adults')->default(1);
            $table->unsignedInteger('num_children')->default(0);
            $table->json('children_ages')->nullable();
            $table->text('modifications')->nullable(); // Free text for add/remove locations/features
            $table->text('message')->nullable(); // Additional notes to guide
            $table->enum('status', ['pending', 'accepted', 'rejected', 'cancelled'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->onDelete('set null');
            $table->timestamps();

            // Indexes for common queries
            $table->index(['guide_plan_id', 'status']);
            $table->index(['tourist_id', 'status']);
            $table->index('start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_proposals');
    }
};
