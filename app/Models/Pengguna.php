<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Contracts\Auth\Authenticatable; // Use Authenticatable contract
use Illuminate\Support\Facades\Auth; // Add Auth facade for login method
use Illuminate\Http\Request; // Add Request class for type hinting in authenticate method

class Pengguna extends Model implements Authenticatable
{
    use HasFactory, AuthenticatableTrait;

    protected $table = 'pengguna';
    public $timestamps = false;

    // Your model code here


}
