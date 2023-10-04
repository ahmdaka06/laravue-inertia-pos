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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cashier_id')
                ->nullable()
                ->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('product_id')
                ->nullable()
                ->references('id')->on('products')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('qty');
            $table->bigInteger('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
