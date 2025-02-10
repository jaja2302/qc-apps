<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryDelete extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'history_delete';
    protected $guarded = ['id'];

    public $timestamps = false;

    protected $fillable = [
        'tabel',
        'data',
        'delete_by',
        'delete_date'
    ];

    protected $casts = [
        'data' => 'array',
        'delete_date' => 'datetime'
    ];

    // public function deletedBy()
    // {
    //     return $this->belongsTo(User::class, 'delete_by');
    // }
}
