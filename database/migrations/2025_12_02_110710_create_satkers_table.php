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
        Schema::create('satkers', function (Blueprint $table) {
            $table->id();
            $table->string('kode_satker', 20)->unique();
            $table->string('nama_satker');
            $table->string('instansi_induk', 100)->nullable();
            $table->text('alamat')->nullable();
            $table->string('pic_nama', 100)->nullable();
            $table->string('pic_kontak', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('satkers');
    }
};
