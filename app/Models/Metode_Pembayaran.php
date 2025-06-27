<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Metode_Pembayaran extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "metode_pembayarans";
    
    protected $fillable = [
        'nama_bank',
        'no_rek',
        'atas_nama',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // Relasi many-to-many dengan Properties (menggunakan tabel pivot)
    public function properties()
    {
        return $this->belongsToMany(Properties::class, 'properti_has_metode_pembayaran', 'id_metode_pembayaran', 'id_properti');
    }

    // Relasi dengan Payment
    public function payments()
    {
        return $this->hasMany(Payment::class, 'metode_pembayaran_id');
    }

    // Relasi dengan User (creator)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi dengan User (updater)
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Relasi dengan User (deleter)
    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}