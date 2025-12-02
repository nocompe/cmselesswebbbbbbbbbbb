<?php

namespace App\Http\Controllers\Admin;

use App\Models\ActivityLog;
use App\Models\VideoCategory;
use Illuminate\Http\Request;

class VideoCategoryController extends AdminController
{
    public function index()
    {
        $categories = VideoCategory::withCount('videos')
            ->orderBy('order')
            ->paginate(15);

        return view('admin.video-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.video-categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:video_categories'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:100'],
            'color' => ['nullable', 'string', 'max:7'],
            'order' => ['integer'],
            'is_active' => ['boolean'],
        ]);

        $category = VideoCategory::create($validated);

        ActivityLog::logCreate($category, "Creó la categoría '{$category->name}'");

        return redirect()
            ->route('admin.video-categories.index')
            ->with('success', 'Categoría creada correctamente.');
    }

    public function edit(VideoCategory $videoCategory)
    {
        return view('admin.video-categories.edit', compact('videoCategory'));
    }

    public function update(Request $request, VideoCategory $videoCategory)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', "unique:video_categories,slug,{$videoCategory->id}"],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:100'],
            'color' => ['nullable', 'string', 'max:7'],
            'order' => ['integer'],
            'is_active' => ['boolean'],
        ]);

        $oldValues = $videoCategory->toArray();

        $videoCategory->update($validated);

        ActivityLog::logUpdate($videoCategory, $oldValues, "Actualizó la categoría '{$videoCategory->name}'");

        return redirect()
            ->route('admin.video-categories.index')
            ->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(VideoCategory $videoCategory)
    {
        if ($videoCategory->videos()->count() > 0) {
            return back()->with('error', 'No puedes eliminar una categoría que tiene videos.');
        }

        ActivityLog::logDelete($videoCategory, "Eliminó la categoría '{$videoCategory->name}'");

        $videoCategory->delete();

        return redirect()
            ->route('admin.video-categories.index')
            ->with('success', 'Categoría eliminada correctamente.');
    }
}
