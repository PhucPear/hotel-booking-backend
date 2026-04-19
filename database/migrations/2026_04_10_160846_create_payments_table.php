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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->foreignId('payment_method_id')->constrained();
            $table->enum('status', ['pending', 'success', 'failed', 'cancelled'])->default('pending');
            $table->string('transaction_code')->unique();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            // Index
            $table->index('status', 'idx_payments_status');
            $table->index('paid_at', 'idx_payments_paid_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
