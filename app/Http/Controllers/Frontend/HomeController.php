<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Page;
use App\Models\Setting;
use App\Models\Slider;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Obtener página home
        $page = Page::where('slug', 'home')->with('activeSections')->first();

        // Sliders del home
        $sliders = Slider::visible()
            ->position('home')
            ->ordered()
            ->get();

        // FAQs activas
        $faqs = Faq::active()
            ->ordered()
            ->take(6)
            ->get();

        // Estadísticas (pueden venir de settings o ser fijas)
        $stats = [
            'clients' => Setting::get('stat_clients', 500),
            'products' => Setting::get('stat_products', 900),
            'years' => Setting::get('stat_years', 5),
        ];

        return view('frontend.home', compact('page', 'sliders', 'faqs', 'stats'));
    }

    public function about()
    {
        $page = Page::where('slug', 'about')->with('activeSections')->first();

        return view('frontend.about', compact('page'));
    }

    public function contact()
    {
        $page = Page::where('slug', 'contact')->with('activeSections')->first();

        return view('frontend.contact', compact('page'));
    }

    public function sendContact(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        // Aquí puedes enviar email o guardar en BD
        // Mail::to(Setting::get('contact_email'))->send(new ContactFormMail($validated));

        return back()->with('success', 'Mensaje enviado correctamente. Te contactaremos pronto.');
    }
}
