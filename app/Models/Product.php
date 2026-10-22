<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'images',
        'description',
        'meta_title',
        'meta_description',
        'price',
        'compare_price',
        'price_sar',
        'compare_price_sar',
        'is_active',
        'is_featured',
        'is_new_arrival',
        'in_stock',
        'on_sale',
    ];
    protected $casts = [
        'images' => 'array',
        'is_new_arrival' => 'boolean',
        'price_sar' => 'decimal:2',
        'compare_price_sar' => 'decimal:2',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(ProductReview::class)->where('status', 'approved');
    }
}
