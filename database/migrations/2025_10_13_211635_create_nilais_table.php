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
        Schema::create('nilais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('tujuan_pembelajaran_id')->constrained('tujuan_pembelajarans')->onDelete('cascade');
            $table->decimal('nilai', 5, 2);
            $table->timestamps();
            $table->unique(['student_id', 'tujuan_pembelajaran_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilais');
    }
};
