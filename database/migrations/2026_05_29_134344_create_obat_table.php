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
        Schema::create('obat', function (Blueprint $table) {
            $table->id();
            $table->string('kode_obat')->unique();
            $table->string('nama_obat');
            $table->enum('satuan',[0,1,2]); // 0 = Box, 1 = Botol, 2 = Strip
            $table->decimal('harga_offline', 10, 2);
            $table->decimal('harga_online', 10, 2);
            $table->integer('stok')->default(0); 
            $table->integer('stok_maksimal')->default(100);
            $table->decimal('nilai_saw', 5, 4)->default(0.0000); 
            $table->foreignId('kategori_id')->constrained('kategori')->onDelete('cascade');
            // $table->foreignId('pemasok_id')->constrained('pemasok')->onDelete('cascade');
            $table->foreignId('pemasok_id')->nullable()->constrained('pemasok')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obat');
    }
};
