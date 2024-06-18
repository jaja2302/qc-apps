<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formijin extends Model
{
    use HasFactory;
    protected $table = 'form_surat_izins';
    protected $connection = 'mysql3';

    protected $fillable = [
        'user_id',
        'list_units_id',
        'tanggal_keluar',
        'tanggal_kembali',
        'lokasi_tujuan',
        'keperluan',
        'atasan_1',
        'atasan_2',
        'created_at',
        'updated_at',
        'status_bot',
    ];


    public $timestamps = false;
}
