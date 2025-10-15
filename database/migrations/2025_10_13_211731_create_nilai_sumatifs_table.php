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
        Schema::create('nilai_sumatifs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('jadwal_id')->constrained('jadwals')->onDelete('cascade');
            $table->enum('jenis', ['mid', 'uas']);
            $table->decimal('nilai', 5, 2);
            $table->timestamps();
            $table->unique(['student_id', 'jadwal_id', 'jenis']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_sumatifs');
    }
};
