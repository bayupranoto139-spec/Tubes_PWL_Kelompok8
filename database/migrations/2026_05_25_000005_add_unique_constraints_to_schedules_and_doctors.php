<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->unique(['doctor_id', 'day_of_week', 'start_time']);
        });

        Schema::table('doctors', function (Blueprint $table) {
            $table->unique('licence_number');
        });
    }

    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropUnique(['doctor_id', 'day_of_week', 'start_time']);
        });

        Schema::table('doctors', function (Blueprint $table) {
            $table->dropUnique(['licence_number']);
        });
    }
};
