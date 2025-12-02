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
        Schema::table('guide_plans', function (Blueprint $table) {
            $table->boolean('allow_proposals')->default(true)->after('status');
            $table->decimal('min_proposal_price', 10, 2)->nullable()->after('allow_proposals');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guide_plans', function (Blueprint $table) {
            $table->dropColumn(['allow_proposals', 'min_proposal_price']);
        });
    }
};
