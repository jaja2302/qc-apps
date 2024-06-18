<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curahhujanbot extends Model
{
    use HasFactory;

    protected $table = 'curah_hujan_bot';
    protected $connection = 'mysql4';

    protected $fillable = [
        'Date',
        'Afd',
        'Est',
        'CH',
        'afd_id',
        'est_id',
    ];


    public $timestamps = false;
}
