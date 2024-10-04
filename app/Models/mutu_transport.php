<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mutu_transport extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'mutu_transport';

    public function Estate()
    {
        return $this->belongsTo(Estate::class, 'estate', 'est');
    }


    public function Afdeling()
    {
        return $this->belongsTo(Afdeling::class, 'afdeling', 'nama');
    }
}
