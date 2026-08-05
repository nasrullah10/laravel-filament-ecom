<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'client_name',
        'client_location',
        'client_image',
        'type',
        'content',
        'video_file',
        'video_thumbnail',
        'rating',
        'product_name',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'rating' => 'integer',
        'sort_order' => 'integer',
    ];

    // Video file ka URL
    public function getVideoUrlAttribute(): ?string
    {
        return $this->video_file ? asset('storage/' . $this->video_file) : null;
    }

    // Thumbnail URL
    public function getThumbnailUrlAttribute(): ?string
    {
        if ($this->video_thumbnail) {
            return asset('storage/' . $this->video_thumbnail);
        }
        return null;
    }
}