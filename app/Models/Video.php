<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Video extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'description',
        'thumbnail',
        'video_url',
        'video_file',
        'video_type',
        'duration',
        'order',
        'is_active',
        'is_featured',
        'views_count',
        'tags',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'tags' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($video) {
            if (empty($video->slug)) {
                $video->slug = Str::slug($video->title);
            }
        });
    }

    // Relaciones
    public function category()
    {
        return $this->belongsTo(VideoCategory::class, 'category_id');
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
    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail) {
            return asset('storage/' . $this->thumbnail);
        }
        
        // Si es YouTube, obtener thumbnail
        if ($this->video_type === 'youtube' && $this->video_url) {
            $videoId = $this->getYouTubeId();
            if ($videoId) {
                return "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg";
            }
        }
        
        return asset('img/video-placeholder.jpg');
    }

    public function getYouTubeId(): ?string
    {
        if ($this->video_type !== 'youtube') {
            return null;
        }

        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $this->video_url, $matches);
        return $matches[1] ?? null;
    }

    public function getEmbedUrl(): ?string
    {
        if ($this->video_type === 'youtube') {
            $videoId = $this->getYouTubeId();
            return $videoId ? "https://www.youtube.com/embed/{$videoId}" : null;
        }

        if ($this->video_type === 'vimeo') {
            preg_match('/vimeo\.com\/(\d+)/', $this->video_url, $matches);
            $videoId = $matches[1] ?? null;
            return $videoId ? "https://player.vimeo.com/video/{$videoId}" : null;
        }

        if ($this->video_type === 'local' && $this->video_file) {
            return asset('storage/' . $this->video_file);
        }

        return $this->video_url;
    }

    public function getDurationFormattedAttribute(): string
    {
        if (!$this->duration) {
            return '00:00';
        }

        $minutes = floor($this->duration / 60);
        $seconds = $this->duration % 60;
        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'LIKE', "%{$term}%")
              ->orWhere('description', 'LIKE', "%{$term}%");
        });
    }
}
