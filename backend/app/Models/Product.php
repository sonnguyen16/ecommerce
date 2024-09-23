<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'unit',
        'price',
        'discount',
        'quantity',
        'sold',
        'atributes',
        'thumbnail',
        'category_id',
        'shop_id',
        'seo_title',
        'seo_description',
        'seo_url',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
