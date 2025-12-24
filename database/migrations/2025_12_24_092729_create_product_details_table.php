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
        Schema::create('product_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->enum('size', ['XS', 'S', 'M', 'L', 'XL']);
            $table->enum('color', ['Red', 'Blue', 'Green', 'Black', 'White']);
            $table->integer('total')->default(1);
            $table->mediumText('additional_info')->nullable('');
            $table->integer('stock')->default(0);
            $table->integer('rate')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_details');
    }
};
