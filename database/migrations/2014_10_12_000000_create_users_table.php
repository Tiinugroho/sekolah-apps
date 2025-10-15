<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('nip')->unique()->nullable();
            $table->string('nisn')->unique()->nullable();
            
            // PERBAIKAN: Tambahkan 'super_admin' sebagai role pertama
            $table->enum('role', ['super_admin', 'kepala_sekolah', 'tata_usaha', 'guru', 'murid']);
            
            $table->string('golongan')->nullable();
            $table->string('jabatan_guru')->nullable();
            $table->string('password');
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->onDelete('set null');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::table('kelas', function (Blueprint $table) {
            $table->foreignId('walikelas_id')->nullable()->after('nama_kelas')->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('kelas');
    }
};