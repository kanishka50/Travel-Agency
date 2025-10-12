<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guide_plan_vehicle_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guide_plan_id')->constrained('guide_plans')->onDelete('cascade');
            $table->string('photo_path');
            $table->timestamp('uploaded_at')->useCurrent();
            
            $table->index('guide_plan_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guide_plan_vehicle_photos');
    }
};
