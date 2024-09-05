<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vocabulary extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'definition',
        'synonyms',
        'antonyms',
        'type',
        'example',
        'is_revised'
    ];

    protected $casts = [
        'is_revised' => 'boolean',
    ];
}