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
        Schema::create('pengiriman', function (Blueprint $table) {
            $table->id('id_pengiriman');
            $table->foreignId('id_order')->constrained('orders', 'id_order')->onDelete('cascade');
            $table->foreignId('id_driver')->constrained('driver', 'id_driver')->onDelete('cascade');
            $table->string('tipe_tugas'); // e.g. Antar, Jemput
            $table->date('tgl_jadwal');
            $table->string('status_tugas')->default('pending'); // e.g. pending, proses, selesai
            $table->text('catatan_kondisi_alat')->nullable();
            $table->text('bukti_foto_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengiriman');
    }
};
