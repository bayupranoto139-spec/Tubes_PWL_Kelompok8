<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->unsignedBigInteger('patient_enrollment_id')->nullable()->after('id');
            $table->unsignedBigInteger('schedule_id')->nullable()->after('patient_enrollment_id');
            $table->softDeletes();
        });

        // Isi patient_enrollment_id dari patient_id lama
        DB::statement("
            UPDATE appointments
            SET patient_enrollment_id = patient_id
        ");

        Schema::table('appointments', function (Blueprint $table) {
            $table->foreign('patient_enrollment_id')
                  ->references('id')->on('patient_enrollments')
                  ->restrictOnDelete();

            $table->foreign('schedule_id')
                  ->references('id')->on('schedules')
                  ->nullOnDelete();

            // Hapus FK lama patient_id → patients
            $table->dropForeign(['patient_id']);
            $table->dropColumn('patient_id');

            // Index untuk cek kuota per slot per tanggal
            $table->index(['schedule_id', 'scheduled_at', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['patient_enrollment_id']);
            $table->dropForeign(['schedule_id']);
            $table->dropColumn(['patient_enrollment_id', 'schedule_id']);
            $table->dropSoftDeletes();

            $table->unsignedBigInteger('patient_id')->nullable()->after('id');
            $table->foreign('patient_id')->references('id')->on('patients');
        });
    }
};
