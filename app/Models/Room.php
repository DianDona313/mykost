<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rooms';

    protected $fillable = [
        'properti_id',
        'room_name',
        'room_deskription',
        'harga',
        'is_available',
        'fasilitas',
        'foto',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // Relasi ke Properti (Many to One)
    public function properti()
    {
        return $this->belongsTo(Properties::class, 'properti_id');
    }

    public function fasilitas()
    {
        return $this->belongsToMany(Fasilitas::class, 'fasilitas_kamar', 'room_id', 'fasilitas_id');
    }
    public function scopeAvailable($query)
    {
        return $query->where('is_available', '1');
    }

    public function scopeOccupied($query)
    {
        return $query->where('is_available', '0');
    }
}
