<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Traits\RecordsActivity;

class Order extends Model
{
    use RecordsActivity;
    
    protected $table = 'orders';
    
    protected $fillable = [
        'user_id',
        'event_id',
        'status',
        'total',
        'brand',
        'issuer',
        'receipt_no',
        'last4'
    ];

    protected $casts = [
        'total' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function tickets(): BelongsToMany
    {
        return $this->belongsToMany(Ticket::class, 'order_ticket')
                    ->withTimestamps();
    }
    
}