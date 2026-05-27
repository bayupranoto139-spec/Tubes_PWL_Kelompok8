<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bill_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bill_id')->constrained('bills')->cascadeOnDelete();
            $table->enum('item_type', ['consultation', 'medication', 'procedure', 'administration', 'other']);
            $table->string('description');
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('unit_price', 12, 2);
            $table->decimal('subtotal', 12, 2)->comment('quantity * unit_price');
            $table->timestamps();

            $table->index('bill_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bill_items');
    }
};
