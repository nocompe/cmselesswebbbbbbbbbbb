<?php

namespace App\Http\Controllers\Admin;

use App\Models\ActivityLog;
use App\Models\Page;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends AdminController
{
    public function index(Request $request)
    {
        $query = Section::with(['page', 'updater']);

        if ($request->filled('page_id')) {
            $query->where('page_id', $request->page_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->search}%")
                  ->orWhere('identifier', 'LIKE', "%{$request->search}%");
            });
        }

        $sections = $query->orderBy('page_id')
            ->orderBy('order')
            ->paginate(20);

        $pages = Page::orderBy('title')->get();

        return view('admin.sections.index', compact('sections', 'pages'));
    }

    public function edit(Section $section)
    {
        $section->load('page');
        return view('admin.sections.edit', compact('section'));
    }

    public function update(Request $request, Section $section)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'content' => ['nullable'],
            'settings' => ['nullable', 'array'],
            'is_active' => ['boolean'],
        ]);

        $oldValues = $section->toArray();

        $section->update([
            ...$validated,
            'updated_by' => auth()->id(),
        ]);

        ActivityLog::logUpdate(
            $section, 
            $oldValues, 
            "Actualizó la sección '{$section->name}' de la página '{$section->page->title}'"
        );

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Sección actualizada correctamente.',
            ]);
        }

        return back()->with('success', 'Sección actualizada correctamente.');
    }

    public function quickUpdate(Request $request, Section $section)
    {
        $this->authorize('sections.edit');

        $validated = $request->validate([
            'content' => ['nullable'],
        ]);

        $oldContent = $section->content;

        $section->update([
            'content' => $validated['content'],
            'updated_by' => auth()->id(),
        ]);

        ActivityLog::log(
            'update',
            "Editó rápidamente la sección '{$section->name}'",
            $section,
            ['content' => $oldContent],
            ['content' => $validated['content']]
        );

        return response()->json([
            'success' => true,
            'message' => 'Contenido actualizado.',
        ]);
    }

    public function uploadImage(Request $request, Section $section)
    {
        $request->validate([
            'image' => ['required', 'image', 'max:5120'], // 5MB max
        ]);

        $path = $request->file('image')->store('sections', 'public');

        $oldContent = $section->content;

        $section->update([
            'content' => $path,
            'updated_by' => auth()->id(),
        ]);

        ActivityLog::log(
            'upload',
            "Subió imagen para la sección '{$section->name}'",
            $section,
            ['content' => $oldContent],
            ['content' => $path]
        );

        return response()->json([
            'success' => true,
            'path' => $path,
            'url' => asset('storage/' . $path),
        ]);
    }
}
