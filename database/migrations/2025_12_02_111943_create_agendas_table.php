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
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_agenda', 50)->unique();
            $table->foreignId('satker_id')->constrained('satkers')->onDelete('cascade');
            $table->foreignId('jenis_pengelolaan_id')->constrained('jenis_pengelolaans')->onDelete('cascade');
            $table->date('tanggal_masuk')->index();
            $table->date('tanggal_target')->nullable()->comment('Calculated from SLA');
            $table->date('tanggal_selesai')->nullable();
            $table->enum('status', ['masuk', 'verifikasi', 'review', 'disetujui', 'ditolak'])->default('masuk');
            $table->integer('durasi_hari')->nullable()->comment('Auto-calculated duration');
            $table->foreignId('pic_id')->constrained('users')->onDelete('cascade')->comment('Staff handling');
            $table->text('file_uploads')->nullable()->comment('JSON array of file paths');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendas');
    }
};
