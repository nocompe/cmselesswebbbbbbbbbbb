<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Page extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'meta_description',
        'meta_keywords',
        'is_active',
        'order',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
        });
    }

    // Relaciones
    public function sections()
    {
        return $this->hasMany(Section::class)->orderBy('order');
    }

    public function activeSections()
    {
        return $this->hasMany(Section::class)->where('is_active', true)->orderBy('order');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Helpers
    public function getSection(string $identifier)
    {
        return $this->sections()->where('identifier', $identifier)->first();
    }

    public function getSectionContent(string $identifier, $default = null)
    {
        $section = $this->getSection($identifier);
        return $section ? $section->content : $default;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
