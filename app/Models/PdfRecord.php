<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PdfRecord extends Model
{
    protected $fillable = [
        'order_id',
        'file_path',
        'file_name'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
