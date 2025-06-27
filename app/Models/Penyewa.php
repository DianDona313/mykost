<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penyewa extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'penyewas';
    protected $guarded= ['id'];
    
    public function metode_pembayarans()
    {
        return $this->hasMany(Metode_Pembayaran::class);
    }
    // public function historyPesan()
    // {
    //     return $this->hasMany(HistoryPesan::class);
    // }
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
    public function historyPesan()
    {
        return $this->hasOne(HistoryPesan::class);
    }
    public $timestamps = true;

    protected $dates = ['deleted_at'];

    public function payments()
    {
        return $this->hasManyThrough(
            \App\Models\Payment::class,
            \App\Models\Booking::class,
            'penyewa_id',     // Foreign key on bookings table
            'booking_id',     // Foreign key on payments table
            'id',             // Local key on penyewas table
            'id'              // Local key on bookings table
        );
    }
}
