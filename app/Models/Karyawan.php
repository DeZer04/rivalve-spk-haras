<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Karyawan extends Model
{
    use HasFactory, HasRoles;

    protected $primaryKey = 'idkaryawan';

    protected $fillable = [
        'nama',
        'divisi',
        'posisi',
        'foto',
    ];
}
