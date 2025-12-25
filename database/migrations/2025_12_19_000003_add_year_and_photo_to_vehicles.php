<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds year and photo columns to vehicles table for better vehicle information.
     */
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            if (!Schema::hasColumn('vehicles', 'year')) {
                $table->unsignedSmallInteger('year')->nullable()->after('model');
            }
            if (!Schema::hasColumn('vehicles', 'photo')) {
                $table->string('photo')->nullable()->after('description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            if (Schema::hasColumn('vehicles', 'year')) {
                $table->dropColumn('year');
            }
            if (Schema::hasColumn('vehicles', 'photo')) {
                $table->dropColumn('photo');
            }
        });
    }
};
