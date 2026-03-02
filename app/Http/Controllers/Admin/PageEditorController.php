<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteContent;
use Illuminate\Http\Request;

class PageEditorController extends Controller
{
    /**
     * Index — lista de paginas editables.
     */
    public function index()
    {
        return view('admin.page-editor.index', ['activeMenu' => 'page-editor']);
    }

    /**
     * Form de edicion del Home.
     */
    public function editHome()
    {
        $hero       = SiteContent::get('home_hero');
        $businesses = SiteContent::get('home_businesses');
        $pricing    = SiteContent::get('home_pricing');
        $features   = SiteContent::get('home_features');
        $cta        = SiteContent::get('home_cta');

        return view('admin.page-editor.home', [
            'activeMenu' => 'page-editor',
            'hero'       => $hero,
            'businesses' => $businesses,
            'pricing'    => $pricing,
            'features'   => $features,
            'cta'        => $cta,
        ]);
    }

    /**
     * Guardar cambios del Home.
     */
    public function updateHome(Request $request)
    {
        // --- HERO ---
        $heroData = [
            'title'       => $request->input('hero_title'),
            'subtitle'    => $request->input('hero_subtitle'),
            'placeholder' => $request->input('hero_placeholder'),
        ];

        // Tipo de fondo
        $bgType = $request->input('hero_bg_type', 'images');
        $heroData['bg_type'] = $bgType;

        // Imagenes de fondo
        $existing = SiteContent::get('home_hero');
        $keptImages = $request->input('hero_existing_images', []);
        $dir = public_path('images/cms');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        // Eliminar imagenes que el usuario quito del editor
        $oldImages = $existing['images'] ?? [];
        foreach ($oldImages as $oldImg) {
            if (!in_array($oldImg, $keptImages)) {
                $oldPath = public_path($oldImg);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
        }

        $images = $keptImages;
        if ($request->hasFile('hero_images')) {
            foreach ($request->file('hero_images') as $file) {
                $filename = 'hero_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($dir, $filename);
                $images[] = 'images/cms/' . $filename;
            }
        }
        $heroData['images'] = array_values($images);
        $heroData['image'] = $images[0] ?? null;
        if ($request->hasFile('hero_video')) {
            // Eliminar video anterior si existe
            if (!empty($existing['video_path'])) {
                $oldPath = public_path($existing['video_path']);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            $video = $request->file('hero_video');
            $videoName = 'hero_video_' . time() . '.' . $video->getClientOriginalExtension();
            $video->move($dir, $videoName);
            $heroData['video_path'] = 'images/cms/' . $videoName;
        } else {
            // Mantener video existente
            $heroData['video_path'] = $existing['video_path'] ?? null;
        }

        // Palabras rotativas
        $words = array_filter($request->input('hero_words', []), fn($w) => trim($w) !== '');
        $heroData['rotating_words'] = array_values($words);

        // Pills
        $pills = [];
        if ($request->has('pill_icon')) {
            foreach ($request->input('pill_icon', []) as $i => $icon) {
                $label = $request->input("pill_label.{$i}");
                $slug  = $request->input("pill_slug.{$i}");
                if (!empty($label)) {
                    $pills[] = [
                        'icon'  => $icon,
                        'label' => $label,
                        'slug'  => $slug,
                    ];
                }
            }
        }
        $heroData['pills'] = $pills;

        SiteContent::set('home_hero', $heroData);

        // --- NEGOCIOS ---
        SiteContent::set('home_businesses', [
            'title'      => $request->input('businesses_title'),
            'link_text'  => $request->input('businesses_link_text'),
            'btn_text'   => $request->input('businesses_btn_text'),
        ]);

        // --- PRICING ---
        SiteContent::set('home_pricing', [
            'title'    => $request->input('pricing_title'),
            'subtitle' => $request->input('pricing_subtitle'),
        ]);

        // --- FEATURES ---
        $cards = [];
        if ($request->has('feature_icon')) {
            foreach ($request->input('feature_icon', []) as $i => $icon) {
                $title = $request->input("feature_title.{$i}");
                $desc  = $request->input("feature_desc.{$i}");
                if (!empty($title)) {
                    $cards[] = [
                        'icon'        => $icon,
                        'title'       => $title,
                        'description' => $desc,
                    ];
                }
            }
        }

        SiteContent::set('home_features', [
            'title'    => $request->input('features_title'),
            'subtitle' => $request->input('features_subtitle'),
            'cards'    => $cards,
        ]);

        // --- CTA ---
        SiteContent::set('home_cta', [
            'title'          => $request->input('cta_title'),
            'subtitle'       => $request->input('cta_subtitle'),
            'btn1_text'      => $request->input('cta_btn1_text'),
            'btn1_icon'      => $request->input('cta_btn1_icon'),
            'btn2_text'      => $request->input('cta_btn2_text'),
            'btn2_icon'      => $request->input('cta_btn2_icon'),
        ]);

        return redirect()
            ->route('admin.page-editor.home')
            ->with('success', 'Pagina de inicio actualizada correctamente.');
    }
}
