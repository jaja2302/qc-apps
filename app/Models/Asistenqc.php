<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistenqc extends Model
{
    use HasFactory;
    // protected $connection = 'mysql2';

    protected $table = 'asisten_qc';

    protected $guarded = ['id'];
    public $timestamps = false;

    public function User()
    {
        return $this->belongsTo(Pengguna::class, 'user_id', 'user_id');
    }
}
