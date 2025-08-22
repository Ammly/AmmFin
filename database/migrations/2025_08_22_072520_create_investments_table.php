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
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('symbol');
            $table->string('name');
            $table->string('type'); // stock, crypto, bond, fund
            $table->decimal('quantity', 15, 8);
            $table->decimal('purchase_price', 15, 2);
            $table->decimal('current_price', 15, 2);
            $table->decimal('total_value', 15, 2);
            $table->decimal('profit_loss', 15, 2)->default(0);
            $table->decimal('profit_loss_percentage', 5, 2)->default(0);
            $table->timestamp('purchased_at');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
