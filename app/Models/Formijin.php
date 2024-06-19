<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formijin extends Model
{
    use HasFactory;
    protected $table = 'db_izin_kebun';
    // protected $connection = 'mysql3';

    protected $fillable = [
        'user_id',
        'tanggal_keluar',
        'tanggal_kembali',
        'lokasi_tujuan',
        'keperluan',
        'atasan_1',
        'atasan_2',
        'created_at',
        'updated_at',
        'catatan',
        'status',
        'status_bot',
    ];


    public $timestamps = false;
}
