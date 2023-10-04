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
            $table->foreignId('cashier_id')
                ->nullable()
                ->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('customer_id')
                ->nullable()
                ->references('id')->on('customers')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('invoice');
            $table->bigInteger('cash');
            $table->bigInteger('change');
            $table->bigInteger('discount');
            $table->bigInteger('grand_total');
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
