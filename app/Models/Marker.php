<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marker extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'latitude', 'longitude', 'description'];
    
    protected $casts = [
        'added' => 'datetime',
        'edited' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}
