<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medical_records', function (Blueprint $table) {
            $table->dropForeign(['patient_id']);
            $table->dropColumn('patient_id');

            $table->dropForeign(['doctor_id']);
            $table->dropColumn('doctor_id');

            $table->dropForeign(['appointment_id']);
            $table->dropColumn('appointment_id');
        });

        Schema::table('medical_records', function (Blueprint $table) {
            $table->foreignId('appointment_id')
                  ->unique()
                  ->after('id')
                  ->constrained('appointments')
                  ->restrictOnDelete();

            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('medical_records', function (Blueprint $table) {
            $table->dropForeign(['appointment_id']);
            $table->dropUnique(['appointment_id']);
            $table->dropColumn('appointment_id');
            $table->dropSoftDeletes();
        });

        Schema::table('medical_records', function (Blueprint $table) {
            $table->unsignedBigInteger('patient_id')->nullable()->after('id');
            $table->unsignedBigInteger('doctor_id')->nullable()->after('patient_id');
            $table->unsignedBigInteger('appointment_id')->nullable();

            $table->foreign('patient_id')->references('id')->on('patients');
            $table->foreign('doctor_id')->references('id')->on('doctors');
            $table->foreign('appointment_id')->references('id')->on('appointments')->nullOnDelete();
        });
    }
};
