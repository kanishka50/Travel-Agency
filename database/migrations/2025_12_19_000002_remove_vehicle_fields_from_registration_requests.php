<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Removes deprecated vehicle fields from guide_registration_requests table.
     * Vehicle information is now managed separately through the vehicles table
     * after guide approval.
     */
    public function up(): void
    {
        Schema::table('guide_registration_requests', function (Blueprint $table) {
            // Drop vehicle_photos column if exists
            if (Schema::hasColumn('guide_registration_requests', 'vehicle_photos')) {
                $table->dropColumn('vehicle_photos');
            }

            // Drop vehicle_license column if exists
            if (Schema::hasColumn('guide_registration_requests', 'vehicle_license')) {
                $table->dropColumn('vehicle_license');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guide_registration_requests', function (Blueprint $table) {
            // Restore vehicle_photos column
            if (!Schema::hasColumn('guide_registration_requests', 'vehicle_photos')) {
                $table->json('vehicle_photos')->nullable()->after('language_certificates');
            }

            // Restore vehicle_license column
            if (!Schema::hasColumn('guide_registration_requests', 'vehicle_license')) {
                $table->string('vehicle_license')->nullable()->after('vehicle_photos');
            }
        });
    }
};
