<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tourist_id')->constrained('tourists')->onDelete('cascade');
            $table->foreignId('guide_plan_id')->constrained('guide_plans')->onDelete('cascade');
            $table->timestamp('created_at')->useCurrent();
            
            $table->unique(['tourist_id', 'guide_plan_id']);
            $table->index('tourist_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
