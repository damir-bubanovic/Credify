<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = ['name','status','spend','meta'];
    protected $casts = ['meta' => 'array'];
}
