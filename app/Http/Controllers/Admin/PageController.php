<?php

namespace App\Http\Controllers\Admin;

use App\Models\ActivityLog;
use App\Models\Page;
use App\Models\Application;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
class PageController extends AdminController
{

    public function __construct()
    {
        // Aplicar auth+verified a TODO menos a estas 3 acciones públicas:
        $this->middleware(['auth', 'verified'])
            ->except(['trabaja', 'tutoriales', 'storeApplication']);
    }
    public function index()
    {
        $pages = Page::with(['creator', 'sections'])
            ->orderBy('order')
            ->paginate(15);

        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:pages'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ]);

        $page = Page::create([
            ...$validated,
            'created_by' => auth()->id(),
        ]);

        ActivityLog::logCreate($page, "Creó la página {$page->title}");

        return redirect()
            ->route('admin.pages.edit', $page)
            ->with('success', 'Página creada correctamente. Ahora puedes agregar secciones.');
    }

    public function edit(Page $page)
    {
        $page->load('sections');
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', "unique:pages,slug,{$page->id}"],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ]);

        $oldValues = $page->toArray();

        $page->update([
            ...$validated,
            'updated_by' => auth()->id(),
        ]);

        ActivityLog::logUpdate($page, $oldValues, "Actualizó la página {$page->title}");

        return back()->with('success', 'Página actualizada correctamente.');
    }

    public function destroy(Page $page)
    {
        ActivityLog::logDelete($page, "Eliminó la página {$page->title}");

        $page->delete();

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Página eliminada correctamente.');
    }

    // Gestión de secciones
    public function storeSection(Request $request, Page $page)
    {
        $validated = $request->validate([
            'identifier' => ['required', 'string', 'max:100', "unique:sections,identifier,NULL,id,page_id,{$page->id}"],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:text,html,image,video,json'],
            'content' => ['nullable'],
            'order' => ['integer'],
        ]);

        $section = $page->sections()->create([
            ...$validated,
            'updated_by' => auth()->id(),
        ]);

        ActivityLog::logCreate($section, "Agregó la sección {$section->name} a la página {$page->title}");

        return back()->with('success', 'Sección agregada correctamente.');
    }

    // ...

    public function updateSection(Request $request, Page $page, Section $section)
    {
        // 1) Reglas básicas
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'order' => ['integer'],
        ];

        // 2) Reglas para archivo según tipo
        if ($section->type === 'image') {
            $rules['image_file'] = ['nullable', 'image', 'max:2048']; // 2MB
        } elseif ($section->type === 'video') {
            $rules['video_file'] = [
                'nullable',
                'file',
                'mimetypes:video/mp4,video/quicktime,video/x-msvideo',
                'max:51200' // ~50MB
            ];
        }

        $validated = $request->validate($rules);

        $oldValues = $section->toArray();

        // 3) Datos base a actualizar
        $data = [
            'name' => $validated['name'],
            'content' => $validated['content'] ?? $section->content,
            'is_active' => $validated['is_active'] ?? false,
            'order' => $validated['order'] ?? $section->order,
            'updated_by' => auth()->id(),
        ];

        // 4) Si es imagen y se subió archivo -> guardar y usar esa ruta
        if ($section->type === 'image' && $request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('sections/images', 'public');
            $data['content'] = $path; // ej: "sections/images/abc123.jpg"
        }

        // 5) Si es video y se subió archivo -> guardar y usar esa ruta
        if ($section->type === 'video' && $request->hasFile('video_file')) {
            $path = $request->file('video_file')->store('sections/videos', 'public');
            $data['content'] = $path; // ej: "sections/videos/xyz456.mp4"
        }

        // 6) Guardar cambios
        $section->update($data);

        ActivityLog::logUpdate(
            $section,
            $oldValues,
            "Actualizó la sección {$section->name} de la página {$page->title}"
        );

        return back()->with('success', 'Sección actualizada correctamente.');
    }

    public function destroySection(Page $page, Section $section)
    {
        ActivityLog::logDelete($section, "Eliminó la sección {$section->name} de la página {$page->title}");

        $section->delete();

        return back()->with('success', 'Sección eliminada correctamente.');
    }

    public function reorderSections(Request $request, Page $page)
    {
        $validated = $request->validate([
            'sections' => ['required', 'array'],
            'sections.*.id' => ['required', 'exists:sections,id'],
            'sections.*.order' => ['required', 'integer'],
        ]);

        foreach ($validated['sections'] as $item) {
            Section::where('id', $item['id'])
                ->where('page_id', $page->id)
                ->update(['order' => $item['order']]);
        }

        return response()->json(['success' => true]);
    }


    public function trabaja()
    {
        // 1) Cargar la página del CMS, por ejemplo con slug 'trabaja'
        $page = Page::where('slug', 'trabaja')
            ->with('sections')
            ->first();

        // 2) Tus posiciones (las que ya tenías)
        $positions = [
            [
                'title' => 'Asesor/a de Ventas',
                'department' => 'Ventas',
                'type' => 'Tiempo Completo',
                'description' => 'Buscamos asesores de ventas apasionados y orientados a resultados para unirse a nuestro equipo comercial. Serás responsable de atender clientes, gestionar ventas y contribuir al crecimiento de ELESS GROUP.',
                'requirements' => [
                    'Experiencia mínima de 1 año en ventas (preferible en sector retail o tecnología)',
                    'Excelentes habilidades de comunicación y negociación',
                    'Orientación al cliente y capacidad para trabajar por objetivos',
                    'Conocimientos básicos de productos de barbería y belleza (deseable)',
                    'Proactividad, dinamismo y trabajo en equipo',
                    'Manejo básico de herramientas informáticas'
                ],
                'location' => 'Lima, Perú',
                'schedule' => 'Tiempo Completo'
            ],
            [
                'title' => 'Desarrollador Full Stack',
                'department' => 'Tecnología',
                'type' => 'Tiempo Completo',
                'description' => 'Buscamos un desarrollador full stack con experiencia en Laravel y Vue.js para unirse a nuestro equipo de desarrollo.',
                'requirements' => [
                    'Experiencia de 2+ años en desarrollo web',
                    'Dominio de Laravel, PHP, JavaScript',
                    'Conocimientos en Vue.js o React',
                    'Experiencia con bases de datos MySQL/PostgreSQL',
                    'Conocimiento de Git y metodologías ágiles'
                ],
                'location' => 'Lima, Perú',
                'schedule' => 'Tiempo Completo'
            ],
            [
                'title' => 'Soporte Técnico',
                'department' => 'Soporte',
                'type' => 'Tiempo Completo',
                'description' => 'Únete a nuestro equipo de soporte para ayudar a nuestros clientes a resolver problemas técnicos.',
                'requirements' => [
                    'Experiencia en atención al cliente',
                    'Conocimientos básicos de sistemas ERP',
                    'Capacidad de resolución de problemas',
                    'Excelentes habilidades de comunicación',
                    'Disponibilidad para trabajar en turnos rotativos'
                ],
                'location' => 'Lima, Perú',
                'schedule' => 'Turnos Rotativos'
            ],
            [
                'title' => 'Especialista en Marketing Digital',
                'department' => 'Marketing',
                'type' => 'Tiempo Completo',
                'description' => 'Únete a nuestro equipo de marketing para impulsar el crecimiento de nuestra marca online.',
                'requirements' => [
                    'Experiencia en marketing digital y redes sociales',
                    'Conocimientos de SEO y SEM',
                    'Manejo de Google Analytics y Ads',
                    'Creatividad para campañas digitales',
                    'Capacidad analítica y orientación a resultados'
                ],
                'location' => 'Lima, Perú',
                'schedule' => 'Tiempo Completo'
            ]
        ];

        // 3) Pasar page y positions a la vista
        return view('trabaja', compact('page', 'positions'));
    }

    public function tutoriales()
    {
        $page = Page::where('slug', 'tutoriales')
            ->with('sections')
            ->first();

        $defaultTutorials = [
            [
                'id' => 1,
                'cms_alias' => 'erp_intro',
                'title' => 'Introducción al Sistema ERP',
                'description' => 'Conoce la interfaz principal del sistema ERP y sus funcionalidades básicas.',
                'category' => 'erp basico',
                'duration' => '15 min',
                'level' => 'Básico',
                'tags' => ['Introducción', 'Dashboard'],
                'image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=500',
                'video_url' => null,
            ],
            [
                'id' => 2,
                'cms_alias' => 'erp_ventas',
                'title' => 'Gestión de Ventas',
                'description' => 'Aprende a registrar ventas, emitir comprobantes y gestionar clientes.',
                'category' => 'erp basico',
                'duration' => '20 min',
                'level' => 'Básico',
                'tags' => ['Ventas', 'Facturación'],
                'image' => 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=500',
                'video_url' => null,
            ],
            [
                'id' => 3,
                'cms_alias' => 'erp_reportes',
                'title' => 'Reportes Avanzados',
                'description' => 'Genera reportes personalizados y análisis de datos para tu negocio.',
                'category' => 'erp avanzado',
                'duration' => '25 min',
                'level' => 'Avanzado',
                'tags' => ['Reportes', 'Analytics'],
                'image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=500',
                'video_url' => null,
            ],
            [
                'id' => 4,
                'cms_alias' => 'ecommerce_basico',
                'title' => 'Gestión de Productos Online',
                'description' => 'Aprende a agregar, editar y gestionar productos en la tienda online.',
                'category' => 'ecommerce basico',
                'duration' => '18 min',
                'level' => 'Básico',
                'tags' => ['Productos', 'Catálogo'],
                'image' => 'https://images.unsplash.com/photo-1556742111-a301076d9d18?w=500',
                'video_url' => null,
            ],
            [
                'id' => 5,
                'cms_alias' => 'ecommerce_avanzado',
                'title' => 'Optimización SEO',
                'description' => 'Mejora el posicionamiento de tu tienda en buscadores.',
                'category' => 'ecommerce avanzado',
                'duration' => '30 min',
                'level' => 'Avanzado',
                'tags' => ['SEO', 'Marketing'],
                'image' => 'https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=500',
                'video_url' => null,
            ],
            [
                'id' => 6,
                'cms_alias' => 'inventario_basico',
                'title' => 'Control de Inventario',
                'description' => 'Monitorea el stock de tus productos en tiempo real.',
                'category' => 'inventario basico',
                'duration' => '22 min',
                'level' => 'Básico',
                'tags' => ['Stock', 'Almacén'],
                'image' => 'https://images.unsplash.com/photo-1553413077-190dd305871c?w=500',
                'video_url' => null,
            ],
        ];

        // 2) Intentar leer JSON desde el CMS
        $tutorialsJson = $page?->getSectionContent('tutoriales_cards_json');

        if (is_array($tutorialsJson) && !empty($tutorialsJson)) {
            // Section ya nos devolvió un array (type = json)
            $tutorials = $tutorialsJson;
        } elseif (is_string($tutorialsJson) && trim($tutorialsJson) !== '') {
            // Por si en algún caso content viniera como string JSON
            $decoded = json_decode($tutorialsJson, true);
            $tutorials = is_array($decoded) ? $decoded : $defaultTutorials;
        } else {
            $tutorials = $defaultTutorials;
        }

        // Inyectar video_url desde el CMS y normalizar a formato embed
        foreach ($tutorials as &$tutorial) {
            $alias = $tutorial['cms_alias'] ?? null;

            // URL por defecto
            $rawUrl = 'https://www.youtube.com/embed/dQw4w9WgXcQ';

            if ($alias && $page) {
                $identifier = 'tutorial_video_' . $alias;
                $cmsValue = $page->getSectionContent($identifier, null);
                if (!empty($cmsValue)) {
                    $rawUrl = $cmsValue;
                }
            }

            // Convertir cualquier URL de YouTube a /embed/
            $tutorial['video_url'] = $this->normalizeTutorialVideoUrl($rawUrl);
        }
        unset($tutorial);

        return view('tutoriales', compact('page', 'tutorials'));
    }
    // App\Http\Controllers\Admin\PageController.php

    private function normalizeTutorialVideoUrl(?string $url): string
    {
        // URL por defecto si viene vacío
        $default = 'https://www.youtube.com/embed/dQw4w9WgXcQ';

        if (!$url) {
            return $default;
        }

        // Si ya es una URL de embed o vimeo, la dejamos tal cual
        if (str_contains($url, 'youtube.com/embed') || str_contains($url, 'player.vimeo.com')) {
            return $url;
        }

        // Extraer ID de YouTube desde distintos formatos
        if (preg_match('/(?:youtube\\.com\\/(?:watch\\?v=|shorts\\/)|youtu\\.be\\/)([^&?\\s]{11})/', $url, $m)) {
            $videoId = $m[1];
            return 'https://www.youtube.com/embed/' . $videoId;
        }

        // Si no logramos interpretar la URL, devolvemos lo que vino o la default
        return $url ?: $default;
    }
    public function storeApplication(Request $request)
    {
        // Validar los datos
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'position' => 'required|string|max:255',
            'message' => 'nullable|string',
            'cv' => 'required|file|mimes:pdf|max:5120' // Máximo 5MB
        ], [
            'name.required' => 'El nombre es obligatorio',
            'email.required' => 'El email es obligatorio',
            'email.email' => 'El email debe ser válido',
            'phone.required' => 'El teléfono es obligatorio',
            'position.required' => 'Debes seleccionar una posición',
            'cv.required' => 'El CV es obligatorio',
            'cv.mimes' => 'El CV debe ser un archivo PDF',
            'cv.max' => 'El CV no debe superar los 5MB'
        ]);

        try {
            // Guardar el archivo CV
            if ($request->hasFile('cv')) {
                $file = $request->file('cv');
                $fileName = time() . '_' . str_replace(' ', '_', $validated['name']) . '.pdf';
                $cvPath = $file->storeAs('cv', $fileName, 'public');
            }

            // Crear la postulación en la base de datos
            Application::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'position' => $validated['position'],
                'message' => $validated['message'] ?? null,
                'cv_path' => $cvPath,
                'status' => 'pendiente'
            ]);

            // Redirigir con mensaje de éxito
            return redirect()->route('trabaja')
                ->with('success', '¡Tu postulación ha sido enviada exitosamente! Te contactaremos pronto.');

        } catch (\Exception $e) {
            // En caso de error
            return redirect()->back()
                ->with('error', 'Hubo un error al enviar tu postulación. Por favor intenta nuevamente.')
                ->withInput();
        }
    }
}
