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
        Schema::create('layanan_sewa', function (Blueprint $table) {
            $table->id('id');
            $table->string('kategori');
            $table->string('nama_layanan');
            $table->string('satuan');
            $table->decimal('harga', 12, 2);
            $table->text('deskripsi')->nullable();
            $table->boolean('is_paket')->default(false);
            $table->foreignId('id_superadmin')->nullable()->constrained('superadmin', 'id_superadmin')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('layanan_sewa');
    }
};
