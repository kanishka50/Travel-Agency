<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tourist_request_id')->constrained('tourist_requests')->onDelete('cascade');
            $table->foreignId('guide_id')->constrained('guides')->onDelete('cascade');
            $table->integer('bid_number');
            $table->text('proposal_message');
            $table->text('day_by_day_plan');
            $table->decimal('total_price', 10, 2);
            $table->text('price_breakdown')->nullable();
            $table->json('destinations_covered');
            $table->text('accommodation_details')->nullable();
            $table->text('transport_details')->nullable();
            $table->text('included_services')->nullable();
            $table->text('excluded_services')->nullable();
            $table->integer('estimated_days')->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected', 'withdrawn'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();
            
            $table->index('tourist_request_id');
            $table->index('guide_id');
            $table->index('status');
            $table->unique(['tourist_request_id', 'guide_id', 'bid_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bids');
    }
};
