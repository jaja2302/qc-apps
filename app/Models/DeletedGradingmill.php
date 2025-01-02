<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeletedGradingmill extends Model
{
    use HasFactory;
    protected $table = 'deleted_gradingmill';
    protected $connection = 'mysql2';
    protected $guarded = ['id'];

    public $timestamps = false;
}
