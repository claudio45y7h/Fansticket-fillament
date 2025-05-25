<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Seatmap extends Model
{
    protected $table = 'seatmaps';
    protected $fillable = [
        'event_id',
        'viewbox',
        'class',  
        'background_image',
        'sections',
        'polygons',
        
        
    ];

    protected $casts = [
        
        'sections' => 'array',
        'polygons' => 'array',
    ];
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
