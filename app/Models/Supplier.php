<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'contact_person',
        'phone',
        'email',
        'address',
        'supplier_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function operations()
    {
        return $this->hasMany(Operation::class, 'supplier_id');
    }
}