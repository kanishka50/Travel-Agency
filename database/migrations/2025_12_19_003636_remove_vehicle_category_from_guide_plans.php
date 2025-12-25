<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Remove vehicle_category column as it's not in the requirements.
     * Per requirements, guide_plans only need:
     * - vehicle_type
     * - vehicle_capacity
     * - vehicle_ac
     * - vehicle_description (optional)
     */
    public function up(): void
    {
        Schema::table('guide_plans', function (Blueprint $table) {
            if (Schema::hasColumn('guide_plans', 'vehicle_category')) {
                $table->dropColumn('vehicle_category');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guide_plans', function (Blueprint $table) {
            $table->string('vehicle_category')->nullable()->after('vehicle_type');
        });
    }
};
