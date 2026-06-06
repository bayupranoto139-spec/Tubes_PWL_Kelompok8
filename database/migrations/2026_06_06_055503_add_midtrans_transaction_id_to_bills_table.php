<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            if (!Schema::hasColumn('bills', 'midtrans_transaction_id')) {
                $table->string('midtrans_transaction_id')->nullable()->after('snap_token');
            }
        });

        DB::statement("ALTER TABLE bills MODIFY COLUMN payment_method VARCHAR(50) NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->dropColumn('midtrans_transaction_id');
        });
        DB::statement("ALTER TABLE bills MODIFY COLUMN payment_method ENUM('cash','bank_transfer','insurance','qris') NULL");
    }
};
