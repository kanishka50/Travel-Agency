<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tourist_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tourist_id')->constrained('tourists')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->integer('duration_days');
            $table->json('preferred_destinations');
            $table->text('must_visit_places')->nullable();
            $table->integer('num_adults');
            $table->integer('num_children')->default(0);
            $table->json('children_ages')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('dates_flexible')->default(false);
            $table->string('flexibility_range')->nullable();
            $table->decimal('budget_min', 10, 2);
            $table->decimal('budget_max', 10, 2);
            $table->json('trip_focus');
            $table->enum('transport_preference', ['public_transport', 'private_vehicle', 'luxury_vehicle', 'no_preference'])->nullable();
            $table->enum('accommodation_preference', ['budget', 'midrange', 'luxury', 'mixed'])->nullable();
            $table->json('dietary_requirements')->nullable();
            $table->text('accessibility_needs')->nullable();
            $table->text('special_requests')->nullable();
            $table->enum('status', ['open', 'bids_received', 'bid_accepted', 'closed', 'expired'])->default('open');
            $table->integer('bid_count')->default(0);
            $table->date('expires_at');
            $table->timestamps();
            
            $table->index('tourist_id');
            $table->index('status');
            $table->index('start_date');
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tourist_requests');
    }
};
