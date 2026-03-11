<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'link_id',
        'announcement_id',
        'schedule',
        'type',
        'is_active'
    ];

    public function announcement()
    {
        return $this->belongsTo(Announcements::class);
    }

    public function link()
    {
        return $this->belongsTo(Links::class);
    }
}
