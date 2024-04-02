<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class historycron extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'cron_history';
    public $timestamps = false;
}
