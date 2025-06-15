<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'id',
        'customer_id',
        'section',
        'row',
        'seat',
        'info',
        'type',
        'stock',
        'price',
        'gate',
        'barcode',
        'status',
    ];
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            if (empty($product->id)) {
                $product->id = strtoupper(\Illuminate\Support\Str::random(16));
            }
        });
    }

    /**
     * Genera un número aleatorio de 17 dígitos para el código de barras.
     * @return string
     */
    public static function generateBarcode($length = 17)
    {
        $barcode = '';
        for ($i = 0; $i < $length; $i++) {
            $barcode .= random_int(0, 9);
        }
        return $barcode;
    }
    public function invoices()
{
    return $this->belongsToMany(Invoice::class, 'invoice_product'); // tabla pivote
}
}
