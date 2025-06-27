<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HistoryPengeluaran extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'property_id',
        'kategori_pengeluaran_id',
        'nama_pengeluaran',
        'jumlah_pengeluaran',
        'tanggal_pengeluaran',
        'foto',
        'penanggung_jawab',
        'deskripsi',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $dates = ['deleted_at'];

    // Relasi ke Properti
    public function property()
    {
        return $this->belongsTo(Properties::class);
    }

    // Relasi ke Kategori Pengeluaran
    public function kategoriPengeluaran()
    {
        return $this->belongsTo(Kategori_Pengeluaran::class);
    }
}
