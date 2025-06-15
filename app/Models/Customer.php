<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';

    protected $fillable = [
        'name',
        'last_name',
        'activo',
        'email',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

public function invoices()
{
    return $this->hasMany(Invoice::class);
}
    
    /**
     * Get the orders associated with the customer.
     */
};
