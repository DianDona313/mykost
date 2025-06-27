<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bookings'; // Nama tabel dalam database

    protected $fillable = [
        'property_id',
        'penyewa_id',
        'room_id',
        'start_date',
        'end_date',
        'durasisewa',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function property()
    {
        return $this->belongsTo(Properties::class, 'property_id');
    }

    public function penyewa()
    {
        return $this->belongsTo(Penyewa::class, 'penyewa_id');
    }
    public function payment()
    {
        return $this->hasOne(Payment::class, 'id', 'payment_id');
    }
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}