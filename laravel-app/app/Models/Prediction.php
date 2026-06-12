<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prediction extends Model
{
    protected $fillable = [
        'image_path',
        'result',
        'confidence',
        'probabilities',
        'status',
    ];
}