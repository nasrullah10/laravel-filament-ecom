<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    protected $fillable = [
        'product_id', 'user_id', 'order_id', 'rating', 'title', 'comment',
        'images', 'videos', 'status', 'is_verified_purchase', 'approved_at',
    ];

    protected $casts = [
        'images' => 'array',
        'videos' => 'array',
        'is_verified_purchase' => 'boolean',
        'approved_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(function (ProductReview $review): void {
            if ($review->status === 'approved' && ! $review->approved_at) {
                $review->approved_at = now();
            }

            if ($review->status !== 'approved') {
                $review->approved_at = null;
            }
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }
}
