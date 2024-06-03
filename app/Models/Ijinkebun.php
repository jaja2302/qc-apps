<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ijinkebun extends Model
{
    use HasFactory;

    protected $table = 'unit';
    protected $connection = 'mysql3';


    public $timestamps = false;
}
