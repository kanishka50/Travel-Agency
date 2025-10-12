<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('full_name');
            $table->enum('role', ['super_admin', 'content_moderator', 'finance_admin', 'complaint_manager', 'registration_manager']);
            $table->string('phone', 50)->nullable();
            $table->timestamps();
            
            $table->index('role');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
