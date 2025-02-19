<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartementUser extends Model
{
    use HasFactory;

    protected $table = 'department_user';


    public function users()
    {
        return $this->hasMany(Pengguna::class, 'user_id', 'user_id');
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class, 'department_id', 'id');
    }
}
