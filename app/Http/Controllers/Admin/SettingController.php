<?php

namespace App\Http\Controllers\Admin;

use App\Models\ActivityLog;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends AdminController
{
    public function index()
    {
        $settings = Setting::orderBy('group')
            ->orderBy('order')
            ->get()
            ->groupBy('group');

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $settings = $request->input('settings', []);

        foreach ($settings as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            
            if (!$setting) continue;

            // Manejar archivos
            if ($setting->type === 'image' || $setting->type === 'file') {
                if ($request->hasFile("settings.{$key}")) {
                    // Eliminar archivo anterior
                    if ($setting->value) {
                        Storage::disk('public')->delete($setting->value);
                    }
                    
                    $value = $request->file("settings.{$key}")->store('settings', 'public');
                } else {
                    continue; // No actualizar si no hay nuevo archivo
                }
            }

            $setting->update(['value' => $value]);
        }

        Setting::clearCache();

        ActivityLog::log('update', 'Actualizó la configuración del sitio');

        return back()->with('success', 'Configuración actualizada correctamente.');
    }

    public function updateGroup(Request $request, string $group)
    {
        $settings = Setting::where('group', $group)->get();

        foreach ($settings as $setting) {
            $value = $request->input($setting->key);

            if ($setting->type === 'image' || $setting->type === 'file') {
                if ($request->hasFile($setting->key)) {
                    if ($setting->value) {
                        Storage::disk('public')->delete($setting->value);
                    }
                    $value = $request->file($setting->key)->store('settings', 'public');
                } else {
                    continue;
                }
            }

            if ($setting->type === 'boolean') {
                $value = $request->has($setting->key);
            }

            $setting->update(['value' => $value]);
        }

        Setting::clearCache();

        ActivityLog::log('update', "Actualizó la configuración del grupo '{$group}'");

        return back()->with('success', 'Configuración actualizada correctamente.');
    }
}
