<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\VideoCategory;
use Illuminate\Http\Request;

class MultimediaController extends Controller
{
    public function index(Request $request)
    {
        $query = Video::with('category')
            ->active()
            ->ordered();

        // Filtro por categoría
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Búsqueda
        if ($request->filled('q')) {
            $query->search($request->q);
        }

        $videos = $query->paginate(12);
        $categories = VideoCategory::active()
            ->withCount(['videos' => function ($q) {
                $q->where('is_active', true);
            }])
            ->ordered()
            ->get();

        // Videos destacados
        $featuredVideos = Video::active()
            ->featured()
            ->take(3)
            ->get();

        // Estadísticas
        $stats = [
            'total' => Video::active()->count(),
            'categories' => VideoCategory::active()->count(),
        ];

        return view('frontend.multimedia.index', compact(
            'videos',
            'categories',
            'featuredVideos',
            'stats'
        ));
    }

    public function show(Video $video)
    {
        // Verificar que esté activo
        if (!$video->is_active) {
            abort(404);
        }

        // Incrementar vistas
        $video->incrementViews();

        // Videos relacionados (misma categoría)
        $relatedVideos = Video::active()
            ->where('category_id', $video->category_id)
            ->where('id', '!=', $video->id)
            ->take(4)
            ->get();

        return view('frontend.multimedia.show', compact('video', 'relatedVideos'));
    }

    public function category(VideoCategory $category)
    {
        if (!$category->is_active) {
            abort(404);
        }

        $videos = Video::active()
            ->byCategory($category->id)
            ->ordered()
            ->paginate(12);

        $categories = VideoCategory::active()
            ->withCount(['videos' => function ($q) {
                $q->where('is_active', true);
            }])
            ->ordered()
            ->get();

        return view('frontend.multimedia.category', compact('category', 'videos', 'categories'));
    }

    // API para cargar más videos (infinite scroll)
    public function loadMore(Request $request)
    {
        $query = Video::with('category')
            ->active()
            ->ordered();

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $videos = $query->paginate(12);

        return response()->json([
            'html' => view('frontend.multimedia._video-grid', compact('videos'))->render(),
            'hasMore' => $videos->hasMorePages(),
            'nextPage' => $videos->currentPage() + 1,
        ]);
    }
}
