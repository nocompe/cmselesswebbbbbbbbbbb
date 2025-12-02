<?php

namespace App\Http\Controllers\Admin;

use App\Models\ActivityLog;
use App\Models\Video;
use App\Models\VideoCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoController extends AdminController
{
    public function index(Request $request)
    {
        $query = Video::with(['category', 'creator']);

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $videos = $query->orderBy('order')->paginate(15);
        $categories = VideoCategory::orderBy('order')->get();

        return view('admin.videos.index', compact('videos', 'categories'));
    }

    public function create()
    {
        $categories = VideoCategory::active()->orderBy('order')->get();
        return view('admin.videos.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:video_categories,id'],
            'description' => ['nullable', 'string'],
            'video_type' => ['required', 'in:youtube,vimeo,local'],
            'video_url' => ['required_if:video_type,youtube,vimeo', 'nullable', 'url'],
            'video_file' => ['required_if:video_type,local', 'nullable', 'mimes:mp4,webm,ogg', 'max:102400'],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'duration' => ['nullable', 'integer'],
            'tags' => ['nullable', 'array'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
            'order' => ['integer'],
        ]);

        // Subir video local si existe
        if ($request->hasFile('video_file')) {
            $validated['video_file'] = $request->file('video_file')->store('videos', 'public');
        }

        // Subir thumbnail si existe
        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $video = Video::create([
            ...$validated,
            'created_by' => auth()->id(),
        ]);

        ActivityLog::logCreate($video, "Creó el video '{$video->title}'");

        return redirect()
            ->route('admin.videos.index')
            ->with('success', 'Video creado correctamente.');
    }

    public function edit(Video $video)
    {
        $categories = VideoCategory::active()->orderBy('order')->get();
        return view('admin.videos.edit', compact('video', 'categories'));
    }

    public function update(Request $request, Video $video)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:video_categories,id'],
            'description' => ['nullable', 'string'],
            'video_type' => ['required', 'in:youtube,vimeo,local'],
            'video_url' => ['required_if:video_type,youtube,vimeo', 'nullable', 'url'],
            'video_file' => ['nullable', 'mimes:mp4,webm,ogg', 'max:102400'],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'duration' => ['nullable', 'integer'],
            'tags' => ['nullable', 'array'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
            'order' => ['integer'],
        ]);

        $oldValues = $video->toArray();

        // Subir nuevo video local si existe
        if ($request->hasFile('video_file')) {
            // Eliminar video anterior
            if ($video->video_file) {
                Storage::disk('public')->delete($video->video_file);
            }
            $validated['video_file'] = $request->file('video_file')->store('videos', 'public');
        }

        // Subir nuevo thumbnail si existe
        if ($request->hasFile('thumbnail')) {
            // Eliminar thumbnail anterior
            if ($video->thumbnail) {
                Storage::disk('public')->delete($video->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $video->update([
            ...$validated,
            'updated_by' => auth()->id(),
        ]);

        ActivityLog::logUpdate($video, $oldValues, "Actualizó el video '{$video->title}'");

        return redirect()
            ->route('admin.videos.index')
            ->with('success', 'Video actualizado correctamente.');
    }

    public function destroy(Video $video)
    {
        // Eliminar archivos
        if ($video->video_file) {
            Storage::disk('public')->delete($video->video_file);
        }
        if ($video->thumbnail) {
            Storage::disk('public')->delete($video->thumbnail);
        }

        ActivityLog::logDelete($video, "Eliminó el video '{$video->title}'");

        $video->delete();

        return redirect()
            ->route('admin.videos.index')
            ->with('success', 'Video eliminado correctamente.');
    }

    public function toggleStatus(Video $video)
    {
        $video->update(['is_active' => !$video->is_active]);

        $status = $video->is_active ? 'activado' : 'desactivado';
        ActivityLog::log('update', "Video '{$video->title}' {$status}", $video);

        return back()->with('success', "Video {$status} correctamente.");
    }

    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'videos' => ['required', 'array'],
            'videos.*.id' => ['required', 'exists:videos,id'],
            'videos.*.order' => ['required', 'integer'],
        ]);

        foreach ($validated['videos'] as $item) {
            Video::where('id', $item['id'])->update(['order' => $item['order']]);
        }

        return response()->json(['success' => true]);
    }
}
