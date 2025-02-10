<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QcGudang extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'qc_gudang';
}
