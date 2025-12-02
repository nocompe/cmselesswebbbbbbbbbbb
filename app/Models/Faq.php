<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'answer',
        'category',
        'order',
        'is_active',
        'views_count',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Helpers
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('question', 'LIKE', "%{$term}%")
              ->orWhere('answer', 'LIKE', "%{$term}%");
        });
    }
}
