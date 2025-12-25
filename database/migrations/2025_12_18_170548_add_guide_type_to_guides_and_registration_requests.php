<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add guide_type to guides table if it doesn't exist
        if (!Schema::hasColumn('guides', 'guide_type')) {
            Schema::table('guides', function (Blueprint $table) {
                $table->enum('guide_type', [
                    'chauffeur_guide',
                    'national_guide',
                    'area_guide',
                    'site_guide',
                    'tourist_driver',
                    'wildlife_tracker',
                    'trekking_guide',
                    'not_specified'
                ])->default('not_specified')->after('full_name');
            });
        }

        // Add guide_type to guide_registration_requests table if it doesn't exist
        if (!Schema::hasColumn('guide_registration_requests', 'guide_type')) {
            Schema::table('guide_registration_requests', function (Blueprint $table) {
                $table->enum('guide_type', [
                    'chauffeur_guide',
                    'national_guide',
                    'area_guide',
                    'site_guide',
                    'tourist_driver',
                    'wildlife_tracker',
                    'trekking_guide',
                    'not_specified'
                ])->default('not_specified')->after('full_name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guides', function (Blueprint $table) {
            $table->dropColumn('guide_type');
        });

        Schema::table('guide_registration_requests', function (Blueprint $table) {
            $table->dropColumn('guide_type');
        });
    }
};
