<?php

namespace App\Http\Controllers\Admin;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends AdminController
{
    public function index()
    {
        $roles = Role::withCount('users', 'permissions')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('group')->get()->groupBy('group');
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles'],
            'description' => ['nullable', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:7'],
            'permissions' => ['array'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'color' => $validated['color'] ?? '#8b5cf6',
            'guard_name' => 'web',
        ]);

        if (!empty($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        ActivityLog::log('create', "Creó el rol {$role->name}", $role);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Rol creado correctamente.');
    }

    public function edit(Role $role)
    {
        // Prevenir edición del rol super-admin
        if ($role->name === 'super-admin') {
            return back()->with('error', 'El rol Super Administrador no puede ser editado.');
        }

        $permissions = Permission::orderBy('group')->get()->groupBy('group');
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        if ($role->name === 'super-admin') {
            return back()->with('error', 'El rol Super Administrador no puede ser modificado.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', "unique:roles,name,{$role->id}"],
            'description' => ['nullable', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:7'],
            'permissions' => ['array'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        $oldPermissions = $role->permissions->pluck('name')->toArray();

        $role->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'color' => $validated['color'] ?? $role->color,
        ]);

        $role->syncPermissions($validated['permissions'] ?? []);

        ActivityLog::log(
            'update',
            "Actualizó el rol {$role->name}",
            $role,
            ['permissions' => $oldPermissions],
            ['permissions' => $validated['permissions'] ?? []]
        );

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Rol actualizado correctamente.');
    }

    public function destroy(Role $role)
    {
        // Prevenir eliminación de roles del sistema
        if (in_array($role->name, ['super-admin', 'admin', 'editor', 'viewer'])) {
            return back()->with('error', 'No puedes eliminar roles del sistema.');
        }

        // Verificar que no tenga usuarios asignados
        if ($role->users()->count() > 0) {
            return back()->with('error', 'No puedes eliminar un rol que tiene usuarios asignados.');
        }

        ActivityLog::log('delete', "Eliminó el rol {$role->name}", $role);

        $role->delete();

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Rol eliminado correctamente.');
    }
}
