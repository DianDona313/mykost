<?php

namespace App\Models;

use App\Models\Metode_Pembayaran;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use PDO;

class Properties extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    // Relasi ke User (creator)
    public function users()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function jenisKost()
    {
        return $this->hasOne(JenisKost::class, 'id', 'jeniskost_id');
    }
    public function peraturans()
    {
        return $this->belongsToMany(Peraturans::class, 'peraturans_has_properties')->withTimestamps();
    }
    public function pengelola()
    {
        return $this->belongsToMany(Pengelola::class, 'pengelola_properties', 'properti_id', 'pengelola_id');
    }
    public function pengelolas()
{
    return $this->belongsToMany(Pengelola::class, 'pengelola_properties', 'properti_id', 'pengelola_id')->withTrashed();
}

    public function rooms()
    {
        return $this->hasMany(Room::class, 'properti_id');
    }
    public function metode_pembayaran()
    {
        return $this->belongsToMany(Metode_Pembayaran::class, 'properti_has_metode_pembayaran', 'id_properti', 'id_metode_pembayaran');
    }
    public function pengeluaranKosts()
    {
        return $this->hasMany(PengeluaranKost::class, 'properti_id');
    }
}
