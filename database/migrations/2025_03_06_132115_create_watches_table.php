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
        Schema::create('watches', function (Blueprint $table) {
            $table->id();
            $table->string('product_name')->unique();
            $table->text('description')->nullable();
            $table->foreignId('brand_id')->constrained('brands')->onDelete('cascade');
            $table->decimal('price', 10, 2);
            $table->boolean('status')->default(true);
            $table->decimal('discount', 10, 2)->default(0.00);
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('watches');
    }
};
