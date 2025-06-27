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
        Schema::table('kategori_pengeluarans', function (Blueprint $table) {
            if (!Schema::hasColumn('kategori_pengeluarans', 'status')) {
                $table->string('status')->nullable()->after('deskripsi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kategori_pengeluarans', function (Blueprint $table) {
            //
        });
    }
};
