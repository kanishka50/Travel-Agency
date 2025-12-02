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
        Schema::create('guide_payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_payment_id')->constrained('booking_payments')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->timestamp('payment_date');
            $table->string('payment_method', 50);
            $table->string('transaction_reference', 255)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('paid_by_admin')->nullable()->constrained('admins')->onDelete('set null');
            $table->timestamps();

            $table->index('booking_payment_id');
            $table->index('payment_date');
            $table->index('paid_by_admin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guide_payment_transactions');
    }
};
