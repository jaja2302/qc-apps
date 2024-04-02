<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Landscape extends Model
{
    use HasFactory;

    protected $table = 'landscape';
    protected $connection = 'mysql2'; // Add this line to set the connection explicitly
    public $timestamps = false;
}
