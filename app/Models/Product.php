<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'category_id',
        'title',
        'description',
        'price',
        'photo',
        'quantity',
        'availability',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function operationItems()
    {
        return $this->hasMany(Operation_item::class);
    }
}
