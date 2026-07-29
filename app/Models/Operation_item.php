<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operation_item extends Model
{
    protected $fillable = [
        'operation_id',
        'product_id',
        'quantity',
        'price',
    ];

    public function operation()
    {
        return $this->belongsTo(Operation::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
