<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SidakTph extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'sidak_tph';
}
