<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_addons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->foreignId('guide_plan_addon_id')->nullable()->constrained('guide_plan_addons')->onDelete('set null');
            $table->string('addon_name');
            $table->text('addon_description');
            $table->integer('day_number');
            $table->decimal('price_per_person', 10, 2);
            $table->integer('num_participants');
            $table->decimal('total_price', 10, 2);
            $table->timestamp('created_at')->useCurrent();
            
            $table->index('booking_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_addons');
    }
};
