<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ticket extends Model
{
    protected $table = 'tickets';
    protected $fillable = [
        'id',
        'event_id',
        'category_id',
        'section',
        'row',
        'seat',
        'info',
        'stock',
    ];
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($ticket) {
            if (empty($ticket->id)) {
                $ticket->id = strtoupper(\Illuminate\Support\Str::random(16));
            }
        });
    }

    protected $casts = [
        'stock' => 'integer',
    ];

    protected $appends = ['type', 'price'];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_ticket')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TicketCategory::class);
    }

    public function getTypeAttribute()
    {
        return $this->category ? $this->category->type : null;
    }

    public function getPriceAttribute()
    {
        return $this->category ? $this->category->price : null;
    }
}
