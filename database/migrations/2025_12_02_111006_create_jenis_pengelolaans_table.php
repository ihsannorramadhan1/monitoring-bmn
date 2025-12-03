<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jenis_pengelolaans', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama_jenis');
            $table->text('deskripsi')->nullable();
            $table->integer('target_hari')->default(14)->comment('Target SLA in days');
            $table->enum('kategori', ['pemanfaatan', 'pemindahtanganan', 'penghapusan', 'sewa', 'lainnya']);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_pengelolaans');
    }
};
