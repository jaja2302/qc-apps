<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estate extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';

    protected $table = 'estate';

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'wil', 'id');
    }

    public function afdeling()
    {
        return $this->hasMany(Afdeling::class, 'estate');
    }
    public function Grading()
    {
        return $this->hasMany(Gradingmill::class, 'estate', 'est');
    }
}
