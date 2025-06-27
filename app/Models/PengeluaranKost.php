<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PengeluaranKost extends Model
{
    use SoftDeletes;

    protected $table = 'pengeluaran_kosts';

    protected $guarded = ['id'];

    /**
     * Relasi ke properti
     */
    public function property()
    {
        return $this->belongsTo(Properties::class, 'properti_id');
    }

    /**
     * Relasi ke kategori pengeluaran
     */
    public function kategoriPengeluaran()
    {
        return $this->belongsTo(Kategori_Pengeluaran::class, 'kategori_pengeluaran_id');
    }

    /**
     * Relasi ke user yang membuat data
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi ke user yang mengupdate data terakhir
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Format jumlah ke rupiah (misalnya)
     */
    public function getJumlahFormatAttribute()
    {
        return number_format($this->jumlah, 0, ',', '.');
    }

    /**
     * Badge status
     */
    public function getStatusBadgeAttribute()
    {
        $color = match ($this->status) {
            'approved' => 'success',
            'rejected' => 'danger',
            default => 'secondary',
        };

        return "<span class='badge badge-{$color}'>" . ucfirst($this->status) . "</span>";
    }
}
