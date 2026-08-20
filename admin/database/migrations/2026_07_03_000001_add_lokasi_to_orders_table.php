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
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('lokasi_lat', 10, 7)->nullable()->after('status_sewa');
            $table->decimal('lokasi_lng', 10, 7)->nullable()->after('lokasi_lat');
            $table->string('lokasi_alamat', 500)->nullable()->after('lokasi_lng');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['lokasi_lat', 'lokasi_lng', 'lokasi_alamat']);
        });
    }
};
