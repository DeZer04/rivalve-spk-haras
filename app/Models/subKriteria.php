<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class subKriteria extends Model
{
    use HasFactory;

    protected $table = "subkriterias";

    protected $fillable = [
        'idset',
        'deskripsi',
        'bobot',
    ];

    public function kriteria() {
        return $this->belongsTo(Kriteria::class, 'idset');
    }
}
