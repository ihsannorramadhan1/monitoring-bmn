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
        Schema::create('agenda_history_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agenda_id')->constrained('agendas')->onDelete('cascade');
            $table->string('status_old', 50)->nullable();
            $table->string('status_new', 50);
            $table->foreignId('changed_by')->constrained('users')->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agenda_history_logs');
    }
};
