<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;

    protected $table = 'jabatan';

    protected $fillable = [
        'nama',
        'created_by'
    ];

    public function Departement()
    {
        return $this->hasMany(Pengguna::class, 'new_jabatan', 'id');
    }
    public $timestamps = false;
}
