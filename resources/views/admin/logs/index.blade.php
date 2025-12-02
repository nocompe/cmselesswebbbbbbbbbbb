@extends('admin.layouts.app')

@section('title', 'Registro de Actividad')

@section('page-header')
<div class="page-header-content">
    <div>
        <h1>Registro de Actividad</h1>
        <p>Historial de acciones en el sistema</p>
    </div>
    <a href="{{ route('admin.logs.export', request()->query()) }}" class="btn btn-secondary">
        <i class="fas fa-download"></i> Exportar
    </a>
</div>
@endsection

@section('content')
<div class="card">
    <!-- Filters -->
    <div class="card-filters">
        <form action="{{ route('admin.logs.index') }}" method="GET" class="filters-form">
            <div class="filter-group">
                <input type="text" name="search" placeholder="Buscar..." 
                       value="{{ request('search') }}" class="form-control">
            </div>
            <div class="filter-group">
                <select name="user" class="form-control">
                    <option value="">Todos los usuarios</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <select name="action" class="form-control">
                    <option value="">Todas las acciones</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                            {{ ucfirst($action) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <input type="date" name="date_from" value="{{ request('date_from') }}" 
                       class="form-control" placeholder="Desde">
            </div>
            <div class="filter-group">
                <input type="date" name="date_to" value="{{ request('date_to') }}" 
                       class="form-control" placeholder="Hasta">
            </div>
            <button type="submit" class="btn btn-secondary">
                <i class="fas fa-filter"></i> Filtrar
            </button>
            @if(request()->hasAny(['search', 'user', 'action', 'date_from', 'date_to']))
                <a href="{{ route('admin.logs.index') }}" class="btn btn-outline">
                    <i class="fas fa-times"></i>
                </a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Usuario</th>
                    <th>Acción</th>
                    <th>Descripción</th>
                    <th>IP</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td>
                            <span class="text-muted">{{ $log->created_at->format('d/m/Y') }}</span>
                            <br>
                            <small>{{ $log->created_at->format('H:i:s') }}</small>
                        </td>
                        <td>
                            @if($log->user)
                                <div class="user-cell">
                                    <img src="{{ $log->user->avatar_url }}" alt="" class="user-avatar-sm">
                                    <span>{{ $log->user->name }}</span>
                                </div>
                            @else
                                <span class="text-muted">Sistema</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-{{ $log->action_color }}">
                                {{ $log->action_label }}
                            </span>
                        </td>
                        <td>{{ Str::limit($log->description, 60) }}</td>
                        <td><code>{{ $log->ip_address }}</code></td>
                        <td>
                            <a href="{{ route('admin.logs.show', $log) }}" class="btn-icon" title="Ver detalles">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            No se encontraron registros
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($logs->hasPages())
        <div class="card-footer">
            {{ $logs->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
