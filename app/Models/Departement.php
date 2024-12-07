<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    use HasFactory;

    protected $table = 'departement';

    protected $fillable = [
        'nama',
        'id_parent',
        'created_by'
    ];

    public function Departement()
    {
        return $this->hasMany(Pengguna::class, 'id_departement', 'id');
    }
    public function users()
    {
        return $this->belongsToMany(Pengguna::class, 'department_user', 'department_id', 'user_id');
    }
    public $timestamps = false;
}
