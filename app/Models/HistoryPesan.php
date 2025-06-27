<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HistoryPesan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'history_pesans';

    protected $fillable = [
        'penyewa_id',
        'pesan',
        'deskripsi',
        'tanggal_mulai',
        'tanggal_selesai',
        'foto',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $dates = ['deleted_at'];

    public function penyewa()
    {
        return $this->hasOne(Penyewa::class, 'id','penyewa_id');
    }
}
