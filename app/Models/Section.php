<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'identifier',
        'name',
        'type',
        'content',
        'settings',
        'order',
        'is_active',
        'updated_by',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
    ];

    // Relaciones
    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Helpers
    public function getContentAttribute($value)
    {
        if ($this->type === 'json') {
            return json_decode($value, true);
        }
        return $value;
    }

    public function setContentAttribute($value)
    {
        if ($this->type === 'json' && is_array($value)) {
            $this->attributes['content'] = json_encode($value);
        } else {
            $this->attributes['content'] = $value;
        }
    }

    public function getSetting(string $key, $default = null)
    {
        return data_get($this->settings, $key, $default);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
