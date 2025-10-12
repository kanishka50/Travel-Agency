<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('notification_type', 100);
            $table->string('subject');
            $table->text('body');
            $table->timestamp('sent_at')->nullable();
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->timestamp('created_at')->useCurrent();
            
            $table->index('user_id');
            $table->index('status');
            $table->index('notification_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};

