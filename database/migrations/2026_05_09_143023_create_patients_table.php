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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('hospital_id')->constrained()->onDelete('cascade');
            $table->string('medical_record_number');
            $table->enum('blood_type', ['A', 'B', 'AB', 'O'])->nullable();
            $table->text('allergies')->nullable();
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_phone');
            $table->string('insurance_provider')->nullable();
            $table->string('insurance_policy_number')->nullable();
            $table->timestamps();

            // Unique: satu user tidak boleh terdaftar dua kali di rumah sakit yang sama
            $table->unique(['user_id', 'hospital_id']);
            // Unique: nomor rekam medis harus unik per rumah sakit
            $table->unique(['hospital_id', 'medical_record_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};