@extends('layouts.app')

@section('title', 'Panel de Administración - Postulaciones')

@push('styles')
    <style>
        .admin-panel {
            padding: 120px 5% 80px;
            min-height: 100vh;
        }

        .admin-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .admin-header h1 {
            font-size: 48px;
            font-weight: 300;
            color: #ffffff;
            margin-bottom: 15px;
        }

        .admin-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            background-color: rgba(139, 92, 246, 0.1);
            max-width: 1200px;
            margin: 0 auto 60px;
        }

        .stat-box {
            background: rgba(20, 20, 20, 0.8);
            border: 1px solid rgba(139, 92, 246, 0.2);
            border-radius: 12px;
            padding: 25px;
            text-align: center;
        }

        .stat-box h3 {
            font-size: 36px;
            font-weight: 700;
            color: #8b5cf6;
            margin-bottom: 8px;
        }

        .stat-box p {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.6);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .applications-table {
            max-width: 1400px;
            margin: 0 auto;
            background: rgba(20, 20, 20, 0.8);
            border: 1px solid rgba(139, 92, 246, 0.2);
            border-radius: 16px;
            overflow: hidden;
        }

        .table-header {
            background: rgba(139, 92, 246, 0.1);
            padding: 20px 30px;
            border-bottom: 1px solid rgba(139, 92, 246, 0.2);
        }

        .table-header h2 {
            font-size: 24px;
            font-weight: 600;
            color: #ffffff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: rgba(139, 92, 246, 0.05);
        }

        th {
            padding: 15px 20px;
            text-align: left;
            font-size: 13px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.8);
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 1px solid rgba(139, 92, 246, 0.2);
        }

        td {
            padding: 20px;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.7);
            border-bottom: 1px solid rgba(139, 92, 246, 0.1);
        }

        tr:hover {
            background: rgba(139, 92, 246, 0.05);
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pendiente {
            background: rgba(251, 191, 36, 0.2);
            color: #fbbf24;
        }

        .status-revisado {
            background: rgba(59, 130, 246, 0.2);
            color: #3b82f6;
        }

        .status-entrevista {
            background: rgba(139, 92, 246, 0.2);
            color: #8b5cf6;
        }

        .status-aceptado {
            background: rgba(34, 197, 94, 0.2);
            color: #22c55e;
        }

        .status-rechazado {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }

        .action-btn {
            padding: 8px 12px;
            background: rgba(139, 92, 246, 0.1);
            border: 1px solid rgba(139, 92, 246, 0.3);
            border-radius: 6px;
            color: #8b5cf6;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            margin-right: 5px;
        }

        .action-btn:hover {
            background: #8b5cf6;
            color: white;
        }

        .action-btn.delete {
            background: rgba(239, 68, 68, 0.1);
            border-color: rgba(239, 68, 68, 0.3);
            color: #ef4444;
        }

        .action-btn.delete:hover {
            background: #ef4444;
            color: white;
        }



        select.status-select {
            padding: 6px 10px;
            background: rgba(139, 92, 246, 0.1);
            border: 1px solid rgba(139, 92, 246, 0.3);
            border-radius: 6px;
            color: #8b5cf6;
            font-size: 12px;
            cursor: pointer;
        }

        .logout-form {
            position: absolute;
            top: 0;
            right: 0;
        }

        .logout-btn {
            padding: 8px 14px;
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 6px;
            color: #fca5a5;
            font-size: 13px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s;
        }

        .logout-btn:hover {
            background: #ef4444;
            color: #ffffff;
        }
    </style>
@endpush

@section('content')
    <div class="admin-panel">
        <div class="admin-header" style="position: relative;">
            <h1>Panel de Administración</h1>
            <p style="color: rgba(255, 255, 255, 0.6);">Gestión de Postulaciones</p>

            <form action="{{ route('logout') }}" method="POST" class="logout-form">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Cerrar sesión</span>
                </button>
            </form>
        </div>

        @if(session('success'))
            <div
                style="max-width: 1200px; margin: 0 auto 30px; padding: 15px; background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); border-radius: 8px; color: #22c55e;">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <div class="admin-stats">
            <div class="stat-box">
                <h3>{{ $applications->count() }}</h3>
                <p>Total Postulaciones</p>
            </div>
            <div class="stat-box">
                <h3>{{ $applications->where('status', 'pendiente')->count() }}</h3>
                <p>Pendientes</p>
            </div>
            <div class="stat-box">
                <h3>{{ $applications->where('status', 'entrevista')->count() }}</h3>
                <p>En Entrevista</p>
            </div>
            <div class="stat-box">
                <h3>{{ $applications->where('status', 'aceptado')->count() }}</h3>
                <p>Aceptados</p>
            </div>
        </div>

        <div class="applications-table">
            <div class="table-header">
                <h2>Lista de Postulaciones</h2>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Posición</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $application)
                        <tr>
                            <td><strong>{{ $application->name }}</strong></td>
                            <td>{{ $application->email }}</td>
                            <td>{{ $application->phone }}</td>
                            <td>{{ $application->position }}</td>
                            <td>{{ $application->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <form action="{{ route('admin.applications.update-status', $application->id) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="status-select" onchange="this.form.submit()">
                                        <option value="pendiente" {{ $application->status == 'pendiente' ? 'selected' : '' }}>
                                            Pendiente</option>
                                        <option value="revisado" {{ $application->status == 'revisado' ? 'selected' : '' }}>
                                            Revisado</option>
                                        <option value="entrevista" {{ $application->status == 'entrevista' ? 'selected' : '' }}>
                                            Entrevista</option>
                                        <option value="aceptado" {{ $application->status == 'aceptado' ? 'selected' : '' }}>
                                            Aceptado</option>
                                        <option value="rechazado" {{ $application->status == 'rechazado' ? 'selected' : '' }}>
                                            Rechazado</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <a href="{{ route('admin.applications.download-cv', $application->id) }}" class="action-btn">
                                    <i class="fas fa-download"></i> CV
                                </a>
                                <form action="{{ route('admin.applications.delete', $application->id) }}" method="POST"
                                    style="display: inline;"
                                    onsubmit="return confirm('¿Estás seguro de eliminar esta postulación?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px;">
                                <p style="color: rgba(255, 255, 255, 0.5);">No hay postulaciones aún</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
