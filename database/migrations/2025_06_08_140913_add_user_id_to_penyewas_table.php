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
        Schema::table('penyewas', function (Blueprint $table) {
            if (!Schema::hasColumn('penyewas', 'user_id')) {
                $table->string('user_id')->nullable()->after('foto');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penyewas', function (Blueprint $table) {
            //
        });
    }
};
