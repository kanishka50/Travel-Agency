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
        // Optimize complaints table
        Schema::table('complaints', function (Blueprint $table) {
            // 1. Make booking_id nullable (complaints don't always need a booking)
            $table->foreignId('booking_id')->nullable()->change();

            // 2. Add polymorphic relationship for "against" entity
            $table->string('against_type', 50)->nullable()->after('against_user_id');
            $table->unsignedBigInteger('against_id')->nullable()->after('against_type');

            // 3. Add polymorphic for filed_by (for consistency)
            $table->string('complainant_type', 50)->nullable()->after('filed_by_type');
            $table->unsignedBigInteger('complainant_id')->nullable()->after('complainant_type');

            // 4. Add visibility controls
            $table->boolean('visible_to_defendant')->default(true)->after('resolution_summary');
            $table->boolean('visible_to_public')->default(false)->after('visible_to_defendant');

            // 5. Add indexes for new columns
            $table->index(['against_type', 'against_id']);
            $table->index(['complainant_type', 'complainant_id']);
        });

        // Optimize complaint_responses table
        Schema::table('complaint_responses', function (Blueprint $table) {
            // 1. Make admin_id nullable (tourists/guides can also respond)
            $table->foreignId('admin_id')->nullable()->change();

            // 2. Add polymorphic responder
            $table->string('responder_type', 50)->nullable()->after('admin_id');
            $table->unsignedBigInteger('responder_id')->nullable()->after('responder_type');

            // 3. Expand response types
            DB::statement("ALTER TABLE complaint_responses MODIFY COLUMN response_type ENUM('email', 'internal_note', 'status_update', 'public_note', 'request_info', 'evidence_submission', 'defendant_response', 'complainant_response') NOT NULL");

            // 4. Add visibility controls
            $table->boolean('visible_to_complainant')->default(true)->after('response_type');
            $table->boolean('visible_to_defendant')->default(true)->after('visible_to_complainant');
            $table->boolean('internal_only')->default(false)->after('visible_to_defendant');

            // 5. Add attachments support
            $table->json('attachments')->nullable()->after('response_text');

            // 6. Add indexes
            $table->index(['responder_type', 'responder_id']);
            $table->index('internal_only');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->dropIndex(['against_type', 'against_id']);
            $table->dropIndex(['complainant_type', 'complainant_id']);

            $table->dropColumn([
                'against_type',
                'against_id',
                'complainant_type',
                'complainant_id',
                'visible_to_defendant',
                'visible_to_public',
            ]);

            $table->foreignId('booking_id')->nullable(false)->change();
        });

        Schema::table('complaint_responses', function (Blueprint $table) {
            $table->dropIndex(['responder_type', 'responder_id']);
            $table->dropIndex(['internal_only']);

            $table->dropColumn([
                'responder_type',
                'responder_id',
                'visible_to_complainant',
                'visible_to_defendant',
                'internal_only',
                'attachments',
            ]);

            DB::statement("ALTER TABLE complaint_responses MODIFY COLUMN response_type ENUM('email', 'internal_note') NOT NULL");

            $table->foreignId('admin_id')->nullable(false)->change();
        });
    }
};
