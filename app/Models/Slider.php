<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = [
        'title', 'subtitle', 'description', 'button_text', 'button_link',
        'image', 'mobile_image', 'overlay_color', 'overlay_opacity',
        'text_position', 'text_color', 'sort_order', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'overlay_opacity' => 'integer',
        'sort_order' => 'integer',
    ];
}