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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('total_price', 12, 2)->default(0);
            $table->enum('status', [
                'pending',
                'confirmed',
                'cancelled'
            ])->default('pending');

            $table->timestamps();

            // Index
            $table->index('status', 'idx_bookings_status');
            $table->index('created_at', 'idx_bookings_created');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
