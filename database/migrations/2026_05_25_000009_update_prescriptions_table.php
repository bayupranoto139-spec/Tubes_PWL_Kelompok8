<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->unsignedBigInteger('medication_id')->nullable()->after('medical_record_id');
            $table->unsignedInteger('quantity')->default(1)->after('duration')
                  ->comment('Jumlah unit yang diberikan');
        });

        $fallbackId = DB::table('medications')->insertGetId([
            'name'       => 'Lain-lain',
            'unit'       => 'unit',
            'price'      => 0,
            'is_active'  => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Arahkan semua resep lama ke medication "Lain-lain"
        DB::statement("
            UPDATE prescriptions
            SET medication_id = {$fallbackId}
            WHERE medication_id IS NULL
        ");

        Schema::table('prescriptions', function (Blueprint $table) {
            $table->unsignedBigInteger('medication_id')->nullable(false)->change();
            $table->foreign('medication_id')
                  ->references('id')->on('medications')
                  ->restrictOnDelete();

            $table->dropColumn('medication_name');
        });
    }

    public function down(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropForeign(['medication_id']);
            $table->dropColumn(['medication_id', 'quantity']);
            $table->string('medication_name')->after('medical_record_id');
        });
    }
};
