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
        Schema::create('pengeluaran_kosts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('properti_id');
            $table->unsignedBigInteger('kategori_pengeluaran_id');
            $table->text('keperluan');
            $table->decimal('jumlah', 15, 2);
            $table->date('tanggal_pengeluaran');
            $table->text('keterangan')->nullable();
            $table->string('bukti_pengeluaran')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('properti_id')->references('id')->on('properties')->onDelete('cascade');
            $table->foreign('kategori_pengeluaran_id')->references('id')->on('kategori_pengeluarans')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluaran_kosts');
    }
};
