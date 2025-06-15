<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon; // Import Carbon for date formatting

class Event extends Model
{
    protected $table = 'events';
    protected $fillable = [
        'artist',
        'event',
        'venue',
        'city',
        'date',
        'poster',
        'info',
        'policies',
        'spotify_iframe',
        'venue_iframe',
    ];

    protected $casts = [
        'date' => 'datetime'
    ];

    protected $appends = ['formatted_date'];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->date)->translatedFormat('D d M • h:i a');
    }

   
   
}
