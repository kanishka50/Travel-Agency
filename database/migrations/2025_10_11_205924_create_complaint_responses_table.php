<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaint_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('complaint_id')->constrained('complaints')->onDelete('cascade');
            $table->foreignId('admin_id')->constrained('admins')->onDelete('cascade');
            $table->text('response_text');
            $table->enum('response_type', ['email', 'internal_note']);
            $table->timestamp('created_at')->useCurrent();
            
            $table->index('complaint_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaint_responses');
    }
};
