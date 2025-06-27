<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'payments';

    protected $guarded = ['id'];
    protected $dates = ['deleted_at'];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'id', );
    }

    public function penyewa()
    {
        return $this->hasOne(Penyewa::class, 'id', 'user_id');
    }

    public function metode_pembayaran()
    {
        return $this->hasOne(Metode_Pembayaran::class, 'id', 'payment_method');
    }
}
