<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengelola extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function properties()
    {
        return $this->belongsToMany(Properties::class, 'pengelola_properties', 'pengelola_id', 'properti_id');
    }
}
