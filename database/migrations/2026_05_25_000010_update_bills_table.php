<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->unsignedBigInteger('patient_enrollment_id')->nullable()->after('id');

            $table->enum('payment_method', ['cash', 'bank_transfer', 'insurance', 'qris'])
                  ->nullable()->after('payment_due_date');
            $table->dateTime('payment_date')->nullable()->after('payment_method');
            $table->string('reference_number')->nullable()->after('payment_date')
                  ->comment('Nomor referensi transfer/QRIS/klaim asuransi');

            $table->softDeletes();
        });

        DB::statement("
            UPDATE bills
            SET patient_enrollment_id = patient_id
        ");

        Schema::table('bills', function (Blueprint $table) {
            $table->unsignedBigInteger('patient_enrollment_id')->nullable(false)->change();
            $table->foreign('patient_enrollment_id')
                  ->references('id')->on('patient_enrollments')
                  ->restrictOnDelete();

            $table->dropForeign(['patient_id']);
            $table->dropColumn('patient_id');
        });

        DB::statement("
            ALTER TABLE bills
            MODIFY COLUMN status ENUM('unpaid', 'paid') NOT NULL DEFAULT 'unpaid'
        ");

        DB::statement("
            UPDATE bills SET status = 'unpaid' WHERE status = 'partial'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE bills
            MODIFY COLUMN status ENUM('unpaid', 'partial', 'paid') NOT NULL DEFAULT 'unpaid'
        ");

        Schema::table('bills', function (Blueprint $table) {
            $table->dropForeign(['patient_enrollment_id']);
            $table->dropColumn(['patient_enrollment_id', 'payment_method', 'payment_date', 'reference_number']);
            $table->dropSoftDeletes();

            $table->unsignedBigInteger('patient_id')->nullable()->after('id');
            $table->foreign('patient_id')->references('id')->on('patients');
        });
    }
};
