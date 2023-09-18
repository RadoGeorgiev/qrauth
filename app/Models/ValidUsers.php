<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValidUsers extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'username',
        'secret'
    ];

    public $timestamps = false;
}
