<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patient_medical_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->restrictOnDelete();
            $table->enum('blood_type', ['A', 'B', 'AB', 'O'])->nullable();
            $table->text('allergies')->nullable();
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_phone', 20);
            $table->string('insurance_provider')->nullable();
            $table->string('insurance_policy_number')->nullable();
            $table->timestamps();
        });

        // Migrasi data medis global dari tabel patients yang lama
        DB::statement("
            INSERT INTO patient_medical_infos
                (user_id, blood_type, allergies, emergency_contact_name,
                 emergency_contact_phone, insurance_provider, insurance_policy_number,
                 created_at, updated_at)
            SELECT DISTINCT
                user_id, blood_type, allergies, emergency_contact_name,
                emergency_contact_phone, insurance_provider, insurance_policy_number,
                created_at, updated_at
            FROM patients
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_medical_infos');
    }
};
