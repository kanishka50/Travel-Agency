<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('review_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_id')->constrained('reviews')->onDelete('cascade');
            $table->string('photo_path');
            $table->timestamp('uploaded_at')->useCurrent();
            
            $table->index('review_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review_photos');
    }
};
