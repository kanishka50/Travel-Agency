<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('complaint_number', 50)->unique();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->foreignId('filed_by')->constrained('users')->onDelete('cascade');
            $table->enum('filed_by_type', ['tourist', 'guide']);
            $table->foreignId('against_user_id')->constrained('users')->onDelete('cascade');
            $table->enum('complaint_type', ['service_quality', 'safety_concern', 'unprofessional_behavior', 'payment_issue', 'cancellation_dispute', 'other']);
            $table->string('subject');
            $table->text('description');
            $table->json('evidence_files')->nullable();
            $table->enum('status', ['open', 'under_review', 'resolved', 'closed'])->default('open');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->foreignId('assigned_to')->nullable()->constrained('admins')->onDelete('set null');
            $table->text('admin_notes')->nullable();
            $table->text('resolution_summary')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
            
            $table->index('complaint_number');
            $table->index('booking_id');
            $table->index('status');
            $table->index('assigned_to');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
