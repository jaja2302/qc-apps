<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perumahan extends Model
{
    use HasFactory;

    protected $table = 'perumahan';
    protected $connection = 'mysql2'; // Add this line to set the connection explicitly
    public $timestamps = false;
}
