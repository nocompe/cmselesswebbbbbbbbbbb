<?php

namespace App\Http\Controllers\Admin;

use App\Models\ActivityLog;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends AdminController
{
    public function index()
    {
        $faqs = Faq::orderBy('order')->paginate(20);
        return view('admin.faqs.index', compact('faqs'));
    }

    public function create()
    {
        $categories = Faq::distinct()->pluck('category')->filter();
        return view('admin.faqs.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:500'],
            'answer' => ['required', 'string'],
            'category' => ['nullable', 'string', 'max:100'],
            'order' => ['integer'],
            'is_active' => ['boolean'],
        ]);

        $faq = Faq::create($validated);

        ActivityLog::logCreate($faq, "Creó la FAQ '{$faq->question}'");

        return redirect()
            ->route('admin.faqs.index')
            ->with('success', 'FAQ creada correctamente.');
    }

    public function edit(Faq $faq)
    {
        $categories = Faq::distinct()->pluck('category')->filter();
        return view('admin.faqs.edit', compact('faq', 'categories'));
    }

    public function update(Request $request, Faq $faq)
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:500'],
            'answer' => ['required', 'string'],
            'category' => ['nullable', 'string', 'max:100'],
            'order' => ['integer'],
            'is_active' => ['boolean'],
        ]);

        $oldValues = $faq->toArray();

        $faq->update($validated);

        ActivityLog::logUpdate($faq, $oldValues, "Actualizó la FAQ #{$faq->id}");

        return redirect()
            ->route('admin.faqs.index')
            ->with('success', 'FAQ actualizada correctamente.');
    }

    public function destroy(Faq $faq)
    {
        ActivityLog::logDelete($faq, "Eliminó la FAQ '{$faq->question}'");

        $faq->delete();

        return redirect()
            ->route('admin.faqs.index')
            ->with('success', 'FAQ eliminada correctamente.');
    }

    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'faqs' => ['required', 'array'],
            'faqs.*.id' => ['required', 'exists:faqs,id'],
            'faqs.*.order' => ['required', 'integer'],
        ]);

        foreach ($validated['faqs'] as $item) {
            Faq::where('id', $item['id'])->update(['order' => $item['order']]);
        }

        return response()->json(['success' => true]);
    }
}
