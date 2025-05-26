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
        'section',
        'row',
        'seat',
        'info',
        'type',
        'stock',
        'price',
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
        'price' => 'decimal:2',
        'stock' => 'integer',
        'key' => 'string',

    ];

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
}
