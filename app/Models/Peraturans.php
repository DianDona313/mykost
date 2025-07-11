<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Peraturans extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'peraturans';
    protected $guarded = ['id'];
    protected $dates = ['deleted_at'];

    public function properties()
    {
        return $this->belongsToMany(Properties::class, 'peraturans_has_properties')->withTimestamps();
    }
}
