<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guide_plan_itineraries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guide_plan_id')->constrained('guide_plans')->onDelete('cascade');
            $table->integer('day_number');
            $table->string('day_title');
            $table->text('description');
            $table->string('accommodation_name')->nullable();
            $table->enum('accommodation_type', ['hotel', 'guesthouse', 'resort', 'homestay', 'camping', 'other'])->nullable();
            $table->enum('accommodation_tier', ['budget', 'midrange', 'luxury'])->nullable();
            $table->boolean('breakfast_included')->default(false);
            $table->boolean('lunch_included')->default(false);
            $table->boolean('dinner_included')->default(false);
            $table->text('meal_notes')->nullable();
            $table->timestamp('created_at')->useCurrent();
            
            $table->index('guide_plan_id');
            $table->index(['guide_plan_id', 'day_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guide_plan_itineraries');
    }
};
