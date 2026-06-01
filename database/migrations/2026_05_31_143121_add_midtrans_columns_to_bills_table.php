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
        Schema::table('bills', function (Blueprint $table) {
            $table->string('midtrans_order_id')->nullable()->after('reference_number');
            $table->string('snap_token')->nullable()->after('midtrans_order_id');
            $table->string('payment_method', 50)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->enum('payment_method', [
                'cash', 'bank_transfer', 'insurance', 'qris',
            ])->nullable()->change();
            $table->dropColumn(['midtrans_order_id', 'snap_token']);
        });
    }
};
