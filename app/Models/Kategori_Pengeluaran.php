<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kategori_Pengeluaran extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kategori_pengeluarans';

    protected $fillable = [
        'nama',
        'deskripsi',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $dates = ['deleted_at'];

    /**
     * Relasi ke HistoryPengeluaran (satu kategori pengeluaran bisa memiliki banyak pengeluaran)
     */
    public function historyPengeluarans()
    {
        return $this->hasMany(HistoryPengeluaran::class, 'kategori_pengeluaran_id');
    }
}
