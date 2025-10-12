<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->unique()->constrained('bookings')->onDelete('cascade');
            $table->foreignId('tourist_id')->constrained('tourists')->onDelete('cascade');
            $table->foreignId('guide_id')->constrained('guides')->onDelete('cascade');
            $table->integer('rating');
            $table->text('review_text')->nullable();
            $table->integer('professionalism_rating')->nullable();
            $table->integer('communication_rating')->nullable();
            $table->integer('value_for_money_rating')->nullable();
            $table->integer('itinerary_quality_rating')->nullable();
            $table->boolean('is_visible')->default(true);
            $table->text('admin_hidden_reason')->nullable();
            $table->timestamps();
            
            $table->index('guide_id');
            $table->index('rating');
            $table->index('is_visible');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};