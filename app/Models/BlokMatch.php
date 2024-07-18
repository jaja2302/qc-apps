<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlokMatch extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'blok_matches';
    public $timestamps = false;
    protected $fillable = [
        'est',
        'afd',
        'blok_asli',
        'blok',
    ];
}
