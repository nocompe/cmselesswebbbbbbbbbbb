<?php

namespace App\Http\Controllers\Admin;

use App\Models\ActivityLog;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends AdminController
{
    public function index(Request $request)
    {
        $query = Media::with('uploader');

        if ($request->filled('type')) {
            match ($request->type) {
                'images' => $query->images(),
                'videos' => $query->videos(),
                default => null,
            };
        }

        if ($request->filled('folder')) {
            $query->inFolder($request->folder);
        }

        if ($request->filled('search')) {
            $query->where('name', 'LIKE', "%{$request->search}%");
        }

        $media = $query->latest()->paginate(24);
        $folders = Media::distinct()->pluck('folder')->filter();

        return view('admin.media.index', compact('media', 'folders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'files' => ['required', 'array'],
            'files.*' => ['required', 'file', 'max:10240'], // 10MB max
            'folder' => ['nullable', 'string', 'max:100'],
        ]);

        $uploaded = [];
        $folder = $request->input('folder', 'general');

        foreach ($request->file('files') as $file) {
            $fileName = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs("media/{$folder}", $fileName, 'public');

            $metadata = [];
            if (str_starts_with($file->getMimeType(), 'image/')) {
                $dimensions = getimagesize($file->path());
                if ($dimensions) {
                    $metadata['width'] = $dimensions[0];
                    $metadata['height'] = $dimensions[1];
                }
            }

            $media = Media::create([
                'name' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'disk' => 'public',
                'folder' => $folder,
                'metadata' => $metadata,
                'uploaded_by' => auth()->id(),
            ]);

            $uploaded[] = $media;

            ActivityLog::log('upload', "Subió archivo '{$media->file_name}'", $media);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'files' => $uploaded,
            ]);
        }

        return back()->with('success', count($uploaded) . ' archivo(s) subido(s) correctamente.');
    }

    public function show(Media $media)
    {
        return view('admin.media.show', compact('media'));
    }

    public function update(Request $request, Media $media)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string'],
        ]);

        $media->update($validated);

        return back()->with('success', 'Archivo actualizado correctamente.');
    }

    public function destroy(Media $media)
    {
        ActivityLog::log('delete', "Eliminó archivo '{$media->file_name}'", $media);

        $media->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Archivo eliminado correctamente.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['exists:media,id'],
        ]);

        $count = 0;
        foreach ($request->ids as $id) {
            $media = Media::find($id);
            if ($media) {
                $media->delete();
                $count++;
            }
        }

        ActivityLog::log('delete', "Eliminó {$count} archivos en lote");

        return response()->json([
            'success' => true,
            'deleted' => $count,
        ]);
    }

    // Selector de medios para el editor
    public function picker(Request $request)
    {
        $query = Media::query();

        if ($request->filled('type')) {
            match ($request->type) {
                'images' => $query->images(),
                'videos' => $query->videos(),
                default => null,
            };
        }

        $media = $query->latest()->paginate(20);

        return view('admin.media.picker', compact('media'));
    }
}
