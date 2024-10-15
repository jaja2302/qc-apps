<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gradingmill extends Model
{
    use HasFactory;

    protected $table = 'grading_mill';
    protected $connection = 'mysql2';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function Estate()
    {
        return $this->belongsTo(Estate::class, 'estate', 'est');
    }

    public function Listmill()
    {
        return $this->belongsTo(Listmill::class, 'mill', 'mill');
    }
}
