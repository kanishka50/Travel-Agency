<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guide_plan_addons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guide_plan_id')->constrained('guide_plans')->onDelete('cascade');
            $table->integer('day_number');
            $table->string('addon_name');
            $table->text('addon_description');
            $table->decimal('price_per_person', 10, 2);
            $table->boolean('require_all_participants')->default(false);
            $table->integer('max_participants')->nullable();
            $table->timestamp('created_at')->useCurrent();
            
            $table->index('guide_plan_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guide_plan_addons');
    }
};

