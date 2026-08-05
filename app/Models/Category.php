<?php
// app/Models/Category.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'image',
        'is_active',
        'sort_order',
        'has_size_chart',
        'size_options',
        'size_chart',
        'size_guide_text',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'has_size_chart' => 'boolean',
        'size_options' => 'json',  // ✅ JSON string ko array me convert
        'size_chart' => 'json',    // ✅ JSON string ko array me convert
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order');
    }

    // Safe getter for size_options
    public function getSizeOptionsArrayAttribute(): array
    {
        $options = $this->size_options;
        
        // Agar already array hai
        if (is_array($options)) return $options;
        
        // Agar JSON string hai
        if (is_string($options)) {
            $decoded = json_decode($options, true);
            return is_array($decoded) ? $decoded : [];
        }
        
        return [];
    }
public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
    // Safe getter for size_chart
    public function getSizeChartArrayAttribute(): array
    {
        $chart = $this->size_chart;
        
        if (is_array($chart)) return $chart;
        
        if (is_string($chart)) {
            $decoded = json_decode($chart, true);
            return is_array($decoded) ? $decoded : [];
        }
        
        return [];
    }
}