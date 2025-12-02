<?php

namespace App\Http\Controllers\Admin;

use App\Models\ActivityLog;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends AdminController
{
    public function index()
    {
        $sliders = Slider::orderBy('position')
            ->orderBy('order')
            ->paginate(15);

        return view('admin.sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.sliders.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:5120'],
            'video_url' => ['nullable', 'url'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'button_url' => ['nullable', 'string', 'max:500'],
            'position' => ['required', 'string', 'max:50'],
            'order' => ['integer'],
            'is_active' => ['boolean'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after:starts_at'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('sliders', 'public');
        }

        $slider = Slider::create([
            ...$validated,
            'created_by' => auth()->id(),
        ]);

        ActivityLog::logCreate($slider, "Creó el slider '{$slider->title}'");

        return redirect()
            ->route('admin.sliders.index')
            ->with('success', 'Slider creado correctamente.');
    }

    public function edit(Slider $slider)
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    public function update(Request $request, Slider $slider)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:5120'],
            'video_url' => ['nullable', 'url'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'button_url' => ['nullable', 'string', 'max:500'],
            'position' => ['required', 'string', 'max:50'],
            'order' => ['integer'],
            'is_active' => ['boolean'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after:starts_at'],
        ]);

        $oldValues = $slider->toArray();

        if ($request->hasFile('image')) {
            if ($slider->image) {
                Storage::disk('public')->delete($slider->image);
            }
            $validated['image'] = $request->file('image')->store('sliders', 'public');
        }

        $slider->update($validated);

        ActivityLog::logUpdate($slider, $oldValues, "Actualizó el slider '{$slider->title}'");

        return redirect()
            ->route('admin.sliders.index')
            ->with('success', 'Slider actualizado correctamente.');
    }

    public function destroy(Slider $slider)
    {
        if ($slider->image) {
            Storage::disk('public')->delete($slider->image);
        }

        ActivityLog::logDelete($slider, "Eliminó el slider '{$slider->title}'");

        $slider->delete();

        return redirect()
            ->route('admin.sliders.index')
            ->with('success', 'Slider eliminado correctamente.');
    }
}
