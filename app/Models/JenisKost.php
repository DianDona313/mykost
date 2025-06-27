<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisKost extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jeniskosts';

    protected $fillable = [
        'nama',
        'deskripsi',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $dates = ['deleted_at'];

    /**
     * Relasi ke Properti (satu jenis kost bisa memiliki banyak properti)
     */
    public function properties()
    {
        return $this->hasMany(Properties::class, 'jeniskost_id');
    }
}
