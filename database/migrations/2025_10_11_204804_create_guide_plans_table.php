<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guide_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guide_id')->constrained('guides')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->integer('num_days');
            $table->integer('num_nights');
            $table->string('pickup_location');
            $table->string('dropoff_location');
            $table->json('destinations');
            $table->json('trip_focus_tags');
            $table->decimal('price_per_adult', 10, 2);
            $table->decimal('price_per_child', 10, 2);
            $table->integer('max_group_size');
            $table->integer('min_group_size')->default(1);
            $table->enum('availability_type', ['date_range', 'always_available']);
            $table->date('available_start_date')->nullable();
            $table->date('available_end_date')->nullable();
            $table->string('vehicle_type', 100)->nullable();
            $table->enum('vehicle_category', ['budget', 'midrange', 'luxury'])->nullable();
            $table->integer('vehicle_capacity')->nullable();
            $table->boolean('vehicle_ac')->default(true);
            $table->text('vehicle_description')->nullable();
            $table->json('dietary_options')->nullable();
            $table->text('accessibility_info')->nullable();
            $table->text('cancellation_policy')->nullable();
            $table->text('inclusions');
            $table->text('exclusions');
            $table->string('cover_photo')->nullable();
            $table->enum('status', ['draft', 'active', 'inactive'])->default('draft');
            $table->integer('view_count')->default(0);
            $table->integer('booking_count')->default(0);
            $table->timestamps();
            
            $table->index('guide_id');
            $table->index('status');
            $table->index('availability_type');
            $table->index('price_per_adult');
            $table->index('num_days');
            $table->index(['available_start_date', 'available_end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guide_plans');
    }
};