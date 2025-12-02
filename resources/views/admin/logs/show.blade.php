@extends('admin.layouts.app')

@section('title', 'Detalle de Actividad')

@section('page-header')
<div class="page-header-content">
    <div>
        <h1>Detalle de Actividad</h1>
        <p>{{ $log->description }}</p>
    </div>
    <a href="{{ route('admin.logs.index') }}" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>
@endsection

@section('content')
<div class="detail-grid">
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-info-circle"></i> Información General</h3>
        </div>
        <div class="card-body">
            <div class="detail-row">
                <span class="detail-label">Fecha y hora</span>
                <span>{{ $log->created_at->format('d/m/Y H:i:s') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Usuario</span>
                @if($log->user)
                    <div class="user-cell">
                        <img src="{{ $log->user->avatar_url }}" alt="" class="user-avatar-sm">
                        <a href="{{ route('admin.users.show', $log->user) }}">{{ $log->user->name }}</a>
                    </div>
                @else
                    <span class="text-muted">Sistema</span>
                @endif
            </div>
            <div class="detail-row">
                <span class="detail-label">Acción</span>
                <span class="badge badge-{{ $log->action_color }}">{{ $log->action_label }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Descripción</span>
                <span>{{ $log->description }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Modelo afectado</span>
                <span>{{ $log->model_type ? class_basename($log->model_type) . ' #' . $log->model_id : 'N/A' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Dirección IP</span>
                <code>{{ $log->ip_address }}</code>
            </div>
            <div class="detail-row">
                <span class="detail-label">User Agent</span>
                <small class="text-muted">{{ $log->user_agent }}</small>
            </div>
        </div>
    </div>

    @if($log->old_values || $log->new_values)
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-exchange-alt"></i> Cambios Realizados</h3>
            </div>
            <div class="card-body">
                @foreach($log->changed_fields as $field => $changes)
                    <div class="change-row">
                        <span class="change-field">{{ ucfirst($field) }}</span>
                        <div class="change-values">
                            <span class="change-old">
                                <i class="fas fa-minus-circle text-danger"></i>
                                {{ is_array($changes['old']) ? json_encode($changes['old']) : ($changes['old'] ?? 'vacío') }}
                            </span>
                            <i class="fas fa-arrow-right"></i>
                            <span class="change-new">
                                <i class="fas fa-plus-circle text-success"></i>
                                {{ is_array($changes['new']) ? json_encode($changes['new']) : ($changes['new'] ?? 'vacío') }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
