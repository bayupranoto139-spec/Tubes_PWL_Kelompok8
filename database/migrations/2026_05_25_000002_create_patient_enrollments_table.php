<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patient_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('hospital_id')->constrained('hospitals')->restrictOnDelete();
            $table->string('medical_record_number', 50);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['user_id', 'hospital_id']);
            $table->unique(['hospital_id', 'medical_record_number']);
        });

        // Migrasi data pendaftaran per RS dari tabel patients yang lama
        DB::statement("
            INSERT INTO patient_enrollments
                (id, user_id, hospital_id, medical_record_number, created_at, updated_at)
            SELECT
                id, user_id, hospital_id, medical_record_number, created_at, updated_at
            FROM patients
        ");

        // Sync auto_increment agar INSERT berikutnya tidak bentrok
        $maxId = DB::table('patient_enrollments')->max('id') ?? 0;
        DB::statement("ALTER TABLE patient_enrollments AUTO_INCREMENT = " . ($maxId + 1));
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_enrollments');
    }
};
