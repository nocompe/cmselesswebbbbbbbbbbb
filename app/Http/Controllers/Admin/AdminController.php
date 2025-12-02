<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use Illuminate\Support\Facades\Storage;
class AdminController extends Controller
{
    /**
     * Middleware para verificar que el usuario tiene acceso al admin
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Respuesta JSON estándar para éxito
     */
    protected function successResponse($message, $data = null, $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * Respuesta JSON estándar para error
     */
    protected function errorResponse($message, $errors = null, $code = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }
    public function applications()
    {
        $applications = Application::orderBy('created_at', 'desc')->get();
        return view('admin.applications', compact('applications'));
    }

    public function updateStatus(Request $request, $id)
    {
        $application = Application::findOrFail($id);
        $application->status = $request->status;
        $application->save();

        return redirect()->back()->with('success', 'Estado actualizado correctamente');
    }

    public function downloadCV($id)
    {
        $application = Application::findOrFail($id);
        return Storage::disk('public')->download($application->cv_path);
    }

    public function deleteApplication($id)
    {
        $application = Application::findOrFail($id);

        // Eliminar el archivo CV
        if (Storage::disk('public')->exists($application->cv_path)) {
            Storage::disk('public')->delete($application->cv_path);
        }

        // Eliminar el registro
        $application->delete();

        return redirect()->back()->with('success', 'Postulación eliminada correctamente');
    }
}
