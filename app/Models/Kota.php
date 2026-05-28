<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kota extends Model
{
    use HasFactory;

    protected $table = 'kota';

    protected $fillable = [
        'nama',
        'latitude',
        'longitude',
        'provinsi',
        'pulau',
        'is_overseas',
    ];

    protected $casts = [
        'is_overseas' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
    ];
}
