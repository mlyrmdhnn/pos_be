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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users'); // cashier / creator
            $table->string('order_code')->unique(); // internal order
            $table->integer('total_amount');
            $table->enum('status', [
                'draft',
                'pending',
                'paid',
                'failed',
                'expired',
                'cancelled',
                'refunded'
            ]);
            $table->string('channel'); // pos | online
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
