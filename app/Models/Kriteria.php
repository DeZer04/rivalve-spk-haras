<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;

    protected $table = "kriterias";

    protected $fillable = [
        'set',
        'keterangan',
    ];

    public function subKriterias() {
        return $this->hasMany(subKriteria::class, 'idset');
    }
}
