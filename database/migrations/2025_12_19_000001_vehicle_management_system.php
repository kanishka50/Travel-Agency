<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This migration:
     * 1. Removes deprecated guide_plan_vehicle_photos table
     * 2. Removes deprecated vehicle columns from guides table
     * 3. Creates new vehicle management tables
     */
    public function up(): void
    {
        // ============================================
        // STEP 1: CLEANUP - Remove deprecated items
        // ============================================

        // Drop the guide_plan_vehicle_photos table (redundant - vehicle photos will be in vehicle_photos)
        Schema::dropIfExists('guide_plan_vehicle_photos');

        // Remove deprecated vehicle columns from guides table
        Schema::table('guides', function (Blueprint $table) {
            if (Schema::hasColumn('guides', 'vehicle_type')) {
                $table->dropColumn('vehicle_type');
            }
            if (Schema::hasColumn('guides', 'vehicle_registration')) {
                $table->dropColumn('vehicle_registration');
            }
        });

        // ============================================
        // STEP 2: CREATE - New vehicle management tables
        // ============================================

        // Create vehicles table (Guide's saved vehicles)
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guide_id')->constrained('guides')->onDelete('cascade');
            $table->enum('vehicle_type', ['car', 'van', 'suv', 'minibus', 'bus', 'tuk_tuk', 'motorcycle']);
            $table->string('make', 100);
            $table->string('model', 100);
            $table->string('license_plate', 20);
            $table->unsignedTinyInteger('seating_capacity');
            $table->boolean('has_ac')->default(true);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Unique license plate per guide
            $table->unique(['guide_id', 'license_plate'], 'unique_plate_per_guide');
            $table->index('is_active');
        });

        // Create vehicle_photos table
        Schema::create('vehicle_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->string('photo_path');
            $table->boolean('is_primary')->default(false);
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamp('created_at')->useCurrent();

            $table->index(['vehicle_id', 'sort_order']);
        });

        // Create vehicle_documents table
        Schema::create('vehicle_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->enum('document_type', ['registration', 'insurance']);
            $table->string('document_path');
            $table->timestamp('created_at')->useCurrent();

            $table->index(['vehicle_id', 'document_type']);
        });

        // Create booking_vehicle_assignments table
        Schema::create('booking_vehicle_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->unique()->constrained('bookings')->onDelete('cascade');
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles')->onDelete('set null');
            $table->boolean('is_temporary')->default(false);
            $table->json('temporary_vehicle_data')->nullable();
            $table->timestamp('assigned_at');
            $table->foreignId('assigned_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index('is_temporary');
            $table->index('assigned_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop new tables in reverse order
        Schema::dropIfExists('booking_vehicle_assignments');
        Schema::dropIfExists('vehicle_documents');
        Schema::dropIfExists('vehicle_photos');
        Schema::dropIfExists('vehicles');

        // Recreate deprecated columns in guides table
        Schema::table('guides', function (Blueprint $table) {
            $table->string('vehicle_type')->nullable()->after('license_expiry');
            $table->string('vehicle_registration')->nullable()->after('vehicle_type');
        });

        // Recreate guide_plan_vehicle_photos table
        Schema::create('guide_plan_vehicle_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guide_plan_id')->constrained('guide_plans')->onDelete('cascade');
            $table->string('photo_path');
            $table->timestamp('uploaded_at')->useCurrent();
        });
    }
};
