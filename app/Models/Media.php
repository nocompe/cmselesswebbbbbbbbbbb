<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'file_name',
        'file_path',
        'mime_type',
        'size',
        'disk',
        'alt_text',
        'caption',
        'metadata',
        'folder',
        'uploaded_by',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    // Relaciones
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // Helpers
    public function getUrlAttribute(): string
    {
        return Storage::disk($this->disk)->url($this->file_path);
    }

    public function getFullPathAttribute(): string
    {
        return Storage::disk($this->disk)->path($this->file_path);
    }

    public function getSizeFormattedAttribute(): string
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    public function isVideo(): bool
    {
        return str_starts_with($this->mime_type, 'video/');
    }

    public function isDocument(): bool
    {
        return in_array($this->mime_type, [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function delete(): bool
    {
        // Eliminar archivo físico
        Storage::disk($this->disk)->delete($this->file_path);
        
        return parent::delete();
    }

    // Scopes
    public function scopeImages($query)
    {
        return $query->where('mime_type', 'LIKE', 'image/%');
    }

    public function scopeVideos($query)
    {
        return $query->where('mime_type', 'LIKE', 'video/%');
    }

    public function scopeInFolder($query, string $folder)
    {
        return $query->where('folder', $folder);
    }
}
