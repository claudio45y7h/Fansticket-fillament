<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoices';
    protected $fillable = [
        'id',
        'customer_id',
        'event_id',
    ];

public function customer()
{
    return $this->belongsTo(Customer::class);
}

public function products()
{
    return $this->belongsToMany(Product::class, 'invoice_product'); // tabla pivote
}

public function event()
{
    return $this->belongsTo(Event::class);
}
}
