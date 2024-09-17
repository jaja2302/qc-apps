<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listmill extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';

    protected $table = 'list_mill';

    public function Regional()
    {
        return $this->belongsTo(Regional::class, 'id', 'reg');
    }

    public function Gradingmill()
    {
        return $this->hasMany(Gradingmill::class, 'mill', 'mill');
    }
}
