<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('guide_id_number', 50)->unique();
            $table->string('full_name');
            $table->string('phone', 50);
            $table->string('national_id', 50);
            $table->text('bio')->nullable();
            $table->string('profile_photo')->nullable();
            $table->json('languages')->nullable();
            $table->json('expertise_areas')->nullable();
            $table->json('regions_can_guide')->nullable();
            $table->integer('years_experience')->default(0);
            $table->decimal('average_rating', 3, 2)->default(0.00);
            $table->integer('total_reviews')->default(0);
            $table->integer('total_bookings')->default(0);
            $table->string('license_number')->nullable();
            $table->date('license_expiry')->nullable();
            $table->string('vehicle_type')->nullable();
            $table->string('vehicle_registration')->nullable();
            $table->string('insurance_policy_number')->nullable();
            $table->date('insurance_expiry')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone', 50)->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_account_holder')->nullable();
            $table->decimal('commission_rate', 5, 2)->default(90.00);
            $table->timestamps();
            
            $table->index('guide_id_number');
            $table->index('average_rating');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guides');
    }
};
