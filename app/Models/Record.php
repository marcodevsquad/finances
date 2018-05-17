<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    protected $fillable = [
        'type',
        'date',
        'description',
        'value',
        'category_id'
    ];
}
