<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mutu_ancak extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'mutu_ancak_new';



    // public function estate()
    // {
    //     return $this->belongsTo(Estate::class, 'estate', 'id');
    // }

    // public function deficiencyTrackers()
    // {
    //     return $this->hasMany(DeficiencyTracker::class, 'afd', 'nama');
    // }
}
