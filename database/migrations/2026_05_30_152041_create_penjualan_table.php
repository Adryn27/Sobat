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
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('no_invoice')->unique();
            $table->integer('total_harga');
            $table->integer('bayar');
            $table->integer('kembali');
            $table->enum('jenis_penjualan',[0,1])->default('0'); // 0 = Offline, 1 = Online
            $table->timestamps();
        });

        Schema::create('detail_penjualan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penjualan_id')->constrained('penjualan')->onDelete('cascade');
            $table->foreignId('obat_id')->constrained('obat');
            $table->integer('harga_jual');
            $table->integer('jumlah');
            $table->integer('subtotal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};
