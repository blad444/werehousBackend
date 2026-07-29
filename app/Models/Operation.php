<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{

    protected $fillable = [
        'user_id',
        'manager_id',
        'supplier_id',
        'price',
        'type',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function items()
    {
        return $this->hasMany(Operation_Item::class);
    }
}