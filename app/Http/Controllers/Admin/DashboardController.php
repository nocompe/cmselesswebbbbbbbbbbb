<?php

namespace App\Http\Controllers\Admin;

use App\Models\ActivityLog;
use App\Models\Page;
use App\Models\User;
use App\Models\Video;
use App\Models\VideoCategory;
use Illuminate\Http\Request;

class DashboardController extends AdminController
{
    public function index()
    {
        // Estadísticas generales
        $stats = [
            'users' => User::count(),
            'pages' => Page::count(),
            'videos' => Video::count(),
            'categories' => VideoCategory::count(),
        ];

        // Últimas actividades
        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->take(10)
            ->get();

        // Usuarios recientes
        $recentUsers = User::latest()
            ->take(5)
            ->get();

        // Videos más vistos
        $popularVideos = Video::with('category')
            ->orderBy('views_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'stats',
            'recentActivities',
            'recentUsers',
            'popularVideos'
        ));
    }
}
