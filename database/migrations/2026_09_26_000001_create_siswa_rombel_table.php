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
        if (!Schema::hasTable('siswa_rombel')) {
            Schema::create('siswa_rombel', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('siswa_id');
                $table->unsignedInteger('kelas_id');
                $table->string('tahun_ajaran', 20);
                $table->string('semester', 10); // Ganjil, Genap
                $table->string('status', 20)->default('Aktif'); // Aktif, Lulus, Tinggal, Pindah, etc.
                $table->timestamps();

                $table->index(['siswa_id', 'tahun_ajaran', 'semester']);
                $table->index(['kelas_id', 'tahun_ajaran', 'semester']);
                $table->unique(['siswa_id', 'tahun_ajaran', 'semester'], 'unique_siswa_rombel_period');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa_rombel');
    }
};
