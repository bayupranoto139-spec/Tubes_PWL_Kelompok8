<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medications', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('generic_name')->nullable()->comment('Nama generik/INN');
            $table->string('category', 100)->nullable()->index()
                  ->comment('Analgesik, Antibiotik, Antihipertensi, dll');
            $table->string('unit', 50)->comment('tablet, kapsul, ml, sachet, ampul');
            $table->decimal('price', 12, 2)->default(0)->comment('Harga satuan (HNA/HET)');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medications');
    }
};
