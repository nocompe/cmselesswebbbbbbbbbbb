<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        if ($this->model_type && $this->model_id) {
            return $this->model_type::find($this->model_id);
        }
        return null;
    }

    // Helpers estáticos para registrar actividad
    public static function log(
        string $action,
        string $description,
        ?Model $model = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): self {
        return self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public static function logCreate(Model $model, string $description = null): self
    {
        return self::log(
            'create',
            $description ?? 'Creó ' . class_basename($model) . ' #' . $model->id,
            $model,
            null,
            $model->toArray()
        );
    }

    public static function logUpdate(Model $model, array $oldValues, string $description = null): self
    {
        return self::log(
            'update',
            $description ?? 'Actualizó ' . class_basename($model) . ' #' . $model->id,
            $model,
            $oldValues,
            $model->toArray()
        );
    }

    public static function logDelete(Model $model, string $description = null): self
    {
        return self::log(
            'delete',
            $description ?? 'Eliminó ' . class_basename($model) . ' #' . $model->id,
            $model,
            $model->toArray(),
            null
        );
    }

    public static function logLogin(): self
    {
        return self::log('login', 'Inició sesión en el sistema');
    }

    public static function logLogout(): self
    {
        return self::log('logout', 'Cerró sesión del sistema');
    }

    // Helpers
    public function getChangedFieldsAttribute(): array
    {
        if (!$this->old_values || !$this->new_values) {
            return [];
        }

        $changes = [];
        foreach ($this->new_values as $key => $newValue) {
            $oldValue = $this->old_values[$key] ?? null;
            if ($oldValue !== $newValue) {
                $changes[$key] = [
                    'old' => $oldValue,
                    'new' => $newValue,
                ];
            }
        }
        return $changes;
    }

    public function getActionLabelAttribute(): string
    {
        return match($this->action) {
            'create' => 'Creación',
            'update' => 'Actualización',
            'delete' => 'Eliminación',
            'login' => 'Inicio de sesión',
            'logout' => 'Cierre de sesión',
            'upload' => 'Subida de archivo',
            default => ucfirst($this->action),
        };
    }

    public function getActionColorAttribute(): string
    {
        return match($this->action) {
            'create' => 'success',
            'update' => 'info',
            'delete' => 'danger',
            'login' => 'primary',
            'logout' => 'secondary',
            default => 'secondary',
        };
    }

    // Scopes
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    public function scopeByModel($query, string $modelType, ?int $modelId = null)
    {
        $query->where('model_type', $modelType);
        if ($modelId) {
            $query->where('model_id', $modelId);
        }
        return $query;
    }

    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
