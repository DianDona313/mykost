<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengelola_properties extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pengelola_properties';

    protected $fillable = ['pengelola_id', 'properti_id', 'created_by', 'updated_by', 'deleted_by'];

    public function pengelola()
    {
        return $this->belongsTo(Pengelola::class, 'pengelola_id');
    }


    public function properti()
    {
        return $this->belongsTo(Properties::class, 'properti_id');
    }
}
