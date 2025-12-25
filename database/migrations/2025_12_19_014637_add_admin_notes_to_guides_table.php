<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds admin_notes column for storing private notes about guides.
     * These notes are only visible to admins, not to guides or tourists.
     */
    public function up(): void
    {
        Schema::table('guides', function (Blueprint $table) {
            $table->text('admin_notes')->nullable()->after('commission_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guides', function (Blueprint $table) {
            $table->dropColumn('admin_notes');
        });
    }
};
