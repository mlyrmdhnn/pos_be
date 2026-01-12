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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('uuid', 50)->unique();
            $table->string('name');
            $table->string('description');
            $table->integer('price');
            $table->foreignId('category_id')->constrained('product_categories')->onDelete('cascade');
            $table->integer('quantity');
            $table->integer('sold');
            $table->string('image_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
