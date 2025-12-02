<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'group',
        'key',
        'label',
        'value',
        'type',
        'options',
        'description',
        'is_public',
        'order',
    ];

    protected $casts = [
        'options' => 'array',
        'is_public' => 'boolean',
    ];

    // Cache key
    private static string $cacheKey = 'settings';

    // Helpers estáticos
    public static function get(string $key, $default = null)
    {
        $settings = self::getAllCached();
        return $settings[$key] ?? $default;
    }

    public static function set(string $key, $value): bool
    {
        $setting = self::where('key', $key)->first();
        
        if ($setting) {
            $setting->update(['value' => $value]);
        } else {
            self::create([
                'key' => $key,
                'label' => ucfirst(str_replace('_', ' ', $key)),
                'value' => $value,
            ]);
        }

        self::clearCache();
        return true;
    }

    public static function getAllCached(): array
    {
        return Cache::rememberForever(self::$cacheKey, function () {
            return self::pluck('value', 'key')->toArray();
        });
    }

    public static function getByGroup(string $group): array
    {
        return self::where('group', $group)
            ->orderBy('order')
            ->get()
            ->toArray();
    }

    public static function getPublic(): array
    {
        return self::where('is_public', true)
            ->pluck('value', 'key')
            ->toArray();
    }

    public static function clearCache(): void
    {
        Cache::forget(self::$cacheKey);
    }

    // Boot
    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            self::clearCache();
        });

        static::deleted(function () {
            self::clearCache();
        });
    }

    // Helpers de instancia
    public function getValueAttribute($value)
    {
        return match($this->type) {
            'boolean' => (bool) $value,
            'json' => json_decode($value, true),
            'integer' => (int) $value,
            default => $value,
        };
    }

    public function setValueAttribute($value)
    {
        $this->attributes['value'] = match($this->type) {
            'boolean' => $value ? '1' : '0',
            'json' => is_array($value) ? json_encode($value) : $value,
            default => $value,
        };
    }
}
