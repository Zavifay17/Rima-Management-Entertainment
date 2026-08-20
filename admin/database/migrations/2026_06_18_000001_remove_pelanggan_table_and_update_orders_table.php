<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add columns to orders table with default empty strings to avoid constraint issues during column creation
        Schema::table('orders', function (Blueprint $table) {
            $table->string('nama_pelanggan')->default('');
            $table->string('no_hp_pelanggan')->default('');
            $table->string('email_pelanggan')->default('');
        });

        // 2. Transfer existing data if table exists
        if (Schema::hasTable('pelanggan')) {
            $orders = DB::table('orders')->get();
            foreach ($orders as $order) {
                $pelanggan = DB::table('pelanggan')->where('id_pelanggan', $order->id_pelanggan)->first();
                if ($pelanggan) {
                    DB::table('orders')->where('id_order', $order->id_order)->update([
                        'nama_pelanggan' => $pelanggan->nama,
                        'no_hp_pelanggan' => $pelanggan->no_hp,
                        'email_pelanggan' => $pelanggan->username,
                    ]);
                }
            }
        }

        // 3. Drop foreign key constraint and column id_pelanggan from orders table
        Schema::table('orders', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('orders_id_pelanggan_foreign');
            }
            $table->dropColumn('id_pelanggan');
        });

        // 4. Drop pelanggan table
        Schema::dropIfExists('pelanggan');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->id('id_pelanggan');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('nama');
            $table->string('no_hp');
            $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('id_pelanggan')->nullable()->constrained('pelanggan', 'id_pelanggan')->onDelete('cascade');
        });

        $orders = DB::table('orders')->get();
        foreach ($orders as $order) {
            $idPelanggan = DB::table('pelanggan')->insertGetId([
                'nama' => $order->nama_pelanggan,
                'no_hp' => $order->no_hp_pelanggan,
                'username' => $order->email_pelanggan,
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // default password
                'created_at' => now(),
                'updated_at' => now(),
            ], 'id_pelanggan');

            DB::table('orders')->where('id_order', $order->id_order)->update([
                'id_pelanggan' => $idPelanggan,
            ]);
        }

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['nama_pelanggan', 'no_hp_pelanggan', 'email_pelanggan']);
        });
    }
};
