<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guide_registration_requests', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('phone', 50);
            $table->string('national_id', 50);
            $table->integer('years_experience');
            $table->json('languages');
            $table->json('expertise_areas');
            $table->json('regions_can_guide');
            $table->text('experience_description');
            $table->string('profile_photo');
            $table->string('national_id_document');
            $table->string('driving_license')->nullable();
            $table->string('guide_certificate')->nullable();
            $table->json('language_certificates')->nullable();
            $table->json('vehicle_photos')->nullable();
            $table->string('vehicle_license')->nullable();
            $table->enum('status', ['documents_pending', 'documents_verified', 'interview_scheduled', 'approved', 'rejected'])->default('documents_pending');
            $table->text('admin_notes')->nullable();
            $table->date('interview_date')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('admins')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            
            $table->index('email');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guide_registration_requests');
    }
};

