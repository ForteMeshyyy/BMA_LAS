<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class monthly_poster extends Model
{
    //
    protected $table = 'monthly_poster';    
    protected $fillable = ['title', 'url'];
}
