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
            $table->foreignId('transaction_id')->constrained();
            $table->string('gateway'); // midtrans
            $table->string('method'); // qris, gopay, bank_transfer
            $table->string('status'); // pending, settlement, expire, deny
            $table->string('midtrans_order_id')->unique();
            $table->integer('amount');
            $table->json('midtrans_response');
            $table->timestamps();
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
